<?php
class Database {
    private $host = "localhost"; // à changer avec "mysql" pour l'utilisation de docker
    private $db_name = "ecoride"; // à vérifier
    private $username = "root"; // par défault
    private $password = ""; // par défault
    private $pdo;

    public function getConnection() {
        $this->pdo = null;
        try {
            $this->pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Erreur de connexion à la base de données : " . $exception->getMessage();
        }
        return $this->pdo;
    }
}
?>
