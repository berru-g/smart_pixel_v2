<?php
$dashboard_url = 'http://' . $_SERVER['HTTP_HOST'] . '/smart_phpixel/smart_pixel_v2/public/dashboard.php?user_id=2&demo=true';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Pixel Analytics — Souverain & Éthique</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script data-sp-id="SP_5a52936f" src="http://localhost/smart_phpixel/smart_pixel_v2/public/tracker.js" async></script>
    
    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #5b21b6;
            --accent: #06d6a0;
            --secondary: #0f172a;
            --light: #f8fafc;
            --gray-light: #cbd5e1;
            --radius: 10px;
            --transition: all 0.3s ease;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--secondary);
            color: var(--light);
            overflow-x: hidden;
        }

        /* ===== HEADER ===== */
        header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            padding: 1rem 2rem;
            background: rgba(15,23,42,0.85);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
        }

        .logo-icon {
            background: var(--primary);
            width: 36px;
            height: 36px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        nav a.cta {
            background: var(--accent);
            color: var(--secondary);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        nav a.cta:hover {
            transform: translateY(-2px);
        }

        /* ===== HERO ===== */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            position: relative;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(90deg, #a78bfa, #7dd3fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--gray-light);
            max-width: 700px;
            margin: 1rem auto 2rem;
        }

        .hero a.cta {
            font-size: 1.1rem;
        }

        /* ===== STATS ANIMÉES ===== */
        .stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .stat-card {
            background: rgba(15,23,42,0.7);
            padding: 1.5rem 2rem;
            border-radius: var(--radius);
            min-width: 150px;
            font-weight: 600;
            transition: var(--transition);
        }

        .stat-card h3 {
            font-size: 2rem;
            margin: 0;
            color: var(--accent);
        }

        .stat-card p {
            margin: 0.5rem 0 0;
            color: var(--gray-light);
            font-size: 0.9rem;
        }

        /* ===== DEMO DASHBOARD ===== */
        .demo {
            padding: 4rem 20px;
            display: flex;
            justify-content: center;
        }

        .demo iframe {
            width: 100%;
            max-width: 1000px;
            height: 600px;
            border-radius: var(--radius);
            border: none;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.25);
        }

        /* ===== INTÉGRATION ===== */
        .integration {
            padding: 3rem 20px;
            text-align: center;
        }

        .integration h2 {
            margin-bottom: 1rem;
        }

        .code-snippet {
            background: rgba(15,23,42,0.8);
            color: var(--light);
            padding: 1.5rem;
            border-radius: var(--radius);
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            margin-top: 1rem;
        }

        /* ===== PRICING ===== */
        .pricing {
            padding: 4rem 20px;
            text-align: center;
        }

        .pricing-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .pricing-card {
            background: rgba(15,23,42,0.6);
            border-radius: var(--radius);
            padding: 2rem;
            transition: var(--transition);
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            border: 2px solid var(--accent);
        }

        .pricing-card.featured {
            border: 2px solid var(--accent);
            background: rgba(15,23,42,0.85);
        }

        .pricing-card h3 { margin-top: 0; }
        .price { font-size: 2.5rem; font-weight: 700; margin: 1rem 0; }

        /* ===== FOOTER ===== */
        footer {
            text-align: center;
            padding: 3rem 20px;
            color: var(--gray-light);
        }

        /* ===== RESPONSIVE ===== */
        @media(max-width:768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1rem; }
            .demo iframe { height: 400px; }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <a href="#" class="logo">
            <div class="logo-icon">◰</div>Smart Pixel
        </a>
        <nav>
            <a href="#pricing" class="cta">Voir les offres</a>
        </nav>
    </header>

    <!-- HERO -->
    <section class="hero">
        <h1>Analytics souverains<br>pour développeurs conscients</h1>
        <p>Alternative éthique à Google Analytics. Données hébergées en France, open-source et chiffrées.</p>
        <a href="#demo" class="cta">Voir le dashboard en direct →</a>

        <div class="stats">
            <div class="stat-card"><h3 id="visitors">0</h3><p>Visiteurs uniques</p></div>
            <div class="stat-card"><h3 id="pages">0</h3><p>Pages vues</p></div>
            <div class="stat-card"><h3 id="duration">0s</h3><p>Durée moyenne</p></div>
        </div>
    </section>

    <!-- DEMO DASHBOARD -->
    <section class="demo" id="demo">
        <iframe src="<?php echo htmlspecialchars($dashboard_url); ?>" title="Dashboard Smart Pixel"></iframe>
    </section>

    <!-- INTÉGRATION -->
    <section class="integration">
        <h2>Intégration en 30 secondes</h2>
        <p>Remplacez Google Analytics par une seule ligne de code :</p>
        <div class="code-snippet">
&lt;script src="https://cdn.smart-pixel.fr/v2/pixel.js" data-sp-id="VOTRE_ID"&gt;&lt;/script&gt;
        </div>
    </section>

    <!-- PRICING -->
    <section class="pricing" id="pricing">
        <h2>Plans transparents</h2>
        <div class="pricing-cards">
            <div class="pricing-card">
                <h3>Free</h3>
                <div class="price">0€<span>/mois</span></div>
                <ul>
                    <li>✓ 1 site</li>
                    <li>✓ 10k vues/mois</li>
                    <li>✓ Dashboard basique</li>
                    <li>✓ Support communautaire</li>
                </ul>
                <a href="./public/login.php" class="cta">Commencer</a>
            </div>
            <div class="pricing-card featured">
                <h3>Pro</h3>
                <div class="price">9€<span>/mois</span></div>
                <ul>
                    <li>✓ 10 sites</li>
                    <li>✓ 100k vues/mois</li>
                    <li>✓ Dashboard complet</li>
                    <li>✓ Export CSV/PDF</li>
                    <li>✓ API d'accès</li>
                    <li>✓ Support prioritaire</li>
                </ul>
                <a href="#" class="cta" style="background: var(--accent); color: var(--secondary);">Essai 14 jours</a>
            </div>
            <div class="pricing-card">
                <h3>Business</h3>
                <div class="price">29€<span>/mois</span></div>
                <ul>
                    <li>✓ 50 sites</li>
                    <li>✓ Visites illimitées</li>
                    <li>✓ Toutes features Pro</li>
                    <li>✓ Collaboration équipe</li>
                    <li>✓ Support téléphone</li>
                    <li>✓ Intégrations custom</li>
                </ul>
                <a href="#" class="cta">Contact</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>© 2024 Smart Pixel Analytics — Développé avec <span style="color: #ef4444;">♥</span> et code sur-mesure</p>
    </footer>

    <script>
        // ====== ANIMATION STATS SIMULÉES ======
        let visitors = 1247, pages = 4892, duration = '2m 41s';
        function animateStats() {
            const v = document.getElementById('visitors');
            const p = document.getElementById('pages');
            const d = document.getElementById('duration');

            let vCount = 0, pCount = 0;
            const interval = setInterval(() => {
                if(vCount < visitors) vCount += Math.ceil(visitors/50); else vCount = visitors;
                if(pCount < pages) pCount += Math.ceil(pages/50); else pCount = pages;
                v.textContent = vCount.toLocaleString();
                p.textContent = pCount.toLocaleString();
                d.textContent = duration;
                if(vCount >= visitors && pCount >= pages) clearInterval(interval);
            }, 30);
        }
        animateStats();

        // ====== SMOOTH SCROLL ======
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) window.scrollTo({top: target.offsetTop-80, behavior:'smooth'});
            });
        });
    </script>

</body>
</html>
