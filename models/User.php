<?php

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = "users";

    public $id;
    public $pseudo;
    public $email;
    public $password;
    public $credits;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un utilisateur

    public function create() {
        $query = "INSERT INTO " . $this->table . " (pseudo, email, password, credits) 
                  VALUES (:pseudo, :email, :password, :credits)";

        $stmt = $this->conn->prepare($query);

        $this->pseudo = htmlspecialchars(strip_tags($this->pseudo));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->credits = isset($this->credits) ? $this->credits : 20;

        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":pseudo", $this->pseudo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":credits", $this->credits);

        return $stmt->execute();
    }

    // Récupérer tous les utilisateurs
    public function readAll() {
        $query = "SELECT id, pseudo, email, credits, photo, role FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Récupérer un utilisateur par ID
    public function readOne() {
        $query = "SELECT id, pseudo, email, credits FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->pseudo = $row['pseudo'];
            $this->email = $row['email'];
            $this->credits = $row['credits'];
            return true;
        }
        return false;
    }

    // Mettre à jour un utilisateur
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET pseudo = :pseudo, email = :email, credits = :credits
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->pseudo = htmlspecialchars(strip_tags($this->pseudo));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":pseudo", $this->pseudo);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":credits", $this->credits);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Supprimer un utilisateur
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Voir les crédits
    public function getCredits() {
        $query = "SELECT credits FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Récuperation des admins
    public function getAllUsers() {
        $query = "SELECT id, pseudo, email, role, credits FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function updateUserRole($id, $role) {
        $query = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    
    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>


