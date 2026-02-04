<?php
// reddit_scraper.php
// Ce fichier sera appelé en AJAX depuis le dashboard

require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

if ($_POST['action'] === 'search_reddit') {
    $subreddits = json_decode($_POST['subreddits'], true);
    $keywords = explode("\n", $_POST['keywords']);
    $postLimit = intval($_POST['post_limit']);
    $minKeywords = intval($_POST['min_keywords']);
    
    $results = [];
    
    foreach ($subreddits as $sub) {
        try {
            // VRAI scraping Reddit JSON
            $url = "https://www.reddit.com/r/{$sub}/new.json?limit={$postLimit}";
            $options = [
                'http' => [
                    'header' => "User-Agent: SmartPixel/1.0\r\n"
                ]
            ];
            
            $context = stream_context_create($options);
            $json = @file_get_contents($url, false, $context);
            
            if ($json) {
                $data = json_decode($json, true);
                
                foreach ($data['data']['children'] as $post) {
                    $postData = $post['data'];
                    $text = strtolower($postData['title'] . ' ' . ($postData['selftext'] ?? ''));
                    
                    $matched = [];
                    foreach ($keywords as $kw) {
                        if (stripos($text, strtolower(trim($kw))) !== false) {
                            $matched[] = trim($kw);
                        }
                    }
                    
                    if (count($matched) >= $minKeywords) {
                        $results[] = [
                            'type' => 'post',
                            'subreddit' => $sub,
                            'author' => $postData['author'],
                            'content' => substr($postData['title'] . ' - ' . ($postData['selftext'] ?? ''), 0, 200),
                            'keywords' => $matched,
                            'score' => $postData['score'],
                            'date' => date('Y-m-d H:i', $postData['created_utc']),
                            'url' => 'https://reddit.com' . $postData['permalink']
                        ];
                    }
                    
                    // Scraper aussi les commentaires
                    $comments = getComments($postData['id'], $sub, $keywords, $minKeywords);
                    $results = array_merge($results, $comments);
                }
            }
            
            sleep(1); // Rate limiting
        } catch (Exception $e) {
            continue;
        }
    }
    
    echo json_encode(['success' => true, 'results' => $results]);
}

function getComments($postId, $subreddit, $keywords, $minKeywords) {
    $comments = [];
    
    $url = "https://www.reddit.com/comments/{$postId}.json";
    $options = [
        'http' => [
            'header' => "User-Agent: SmartPixel/1.0\r\n"
        ]
    ];
    
    $context = stream_context_create($options);
    $json = @file_get_contents($url, false, $context);
    
    if ($json) {
        $data = json_decode($json, true);
        
        if (isset($data[1]['data']['children'])) {
            foreach ($data[1]['data']['children'] as $comment) {
                if ($comment['kind'] === 't1') {
                    $commentData = $comment['data'];
                    $text = strtolower($commentData['body'] ?? '');
                    
                    $matched = [];
                    foreach ($keywords as $kw) {
                        if (stripos($text, strtolower(trim($kw))) !== false) {
                            $matched[] = trim($kw);
                        }
                    }
                    
                    if (count($matched) >= $minKeywords) {
                        $comments[] = [
                            'type' => 'comment',
                            'subreddit' => $subreddit,
                            'author' => $commentData['author'],
                            'content' => substr($commentData['body'], 0, 200),
                            'keywords' => $matched,
                            'score' => $commentData['score'],
                            'date' => date('Y-m-d H:i', $commentData['created_utc']),
                            'url' => 'https://reddit.com' . $commentData['permalink']
                        ];
                    }
                }
            }
        }
    }
    
    return $comments;
}
?>