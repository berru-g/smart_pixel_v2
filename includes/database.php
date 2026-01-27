<?php
// includes/database.php
class Database {
    private static $pdo = null;
    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                error_log("Connection failed: " . $e->getMessage());
                // Afficher un message générique à l'utilisateur
                die("Un problème est survenu avec le service de base de données.");
            }
        }
        return self::$pdo;
    }
}
?>