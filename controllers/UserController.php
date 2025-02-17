<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/JwtHandler.php';

class UserController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }
 

    // Récupérer tous les utilisateurs 
    public function getUsers() {
        $user = new User($this->pdo);
        $stmt = $user->readAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }


    
    // Récupérer que les employées
    public function getEmployees() {
  
        $stmt = $this->pdo->prepare("SELECT id, pseudo, email, role FROM users WHERE role = 'employe'");
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        echo json_encode($employees);
    }



    // Récupérer un utilisateur par ID 
    public function getUser($userId) {

        $stmt = $this->pdo->prepare("SELECT id, pseudo, email, credits, role, photo FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            echo json_encode(["error" => "Utilisateur non trouvé"]);
            return;
        }
    
        echo json_encode($user);

    }


    

    // Créer un utilisateur 
    public function createUser() {

        $data = json_decode(file_get_contents("php://input"), true);
    
        if (!isset($data['pseudo'], $data['email'], $data['password'])) {
            echo json_encode(["error" => "Données incomplètes"]);
            return;
        }
    
        // Hash du mot de passe avec password_hash()
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
        $query = "INSERT INTO users (pseudo, email, password, credits, role) 
                  VALUES (:pseudo, :email, :password, 20, 'user')";
    
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":pseudo", $data['pseudo']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $hashedPassword);
    
        if ($stmt->execute()) {
            echo json_encode(["message" => "Compte créé avec succès"]);
        } else {
            echo json_encode(["error" => "Erreur lors de l'inscription"]);
        }

    }




    // Mettre à jour un utilisateur 
    public function updateUser($id) {

        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode(["error" => "Données incomplètes"]);
            return;
        }

        $user = new User($this->pdo);
        $user->id = $id;
        $user->pseudo = $data['pseudo'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->credits = $data['credits'] ?? null;

        if ($user->update()) {
            echo json_encode(["message" => "Mise à jour réussie"]);
        } else {
            echo json_encode(["error" => "Échec de la mise à jour"]);
        }

    }





    public function updateUserRole($id) {

        $data = json_decode(file_get_contents("php://input"), true);
    
        if (!isset($data['role'])) {
            echo json_encode(["error" => "Le rôle est manquant"]);
            return;
        }
    
        // 🔹 Vérification que l'ID existe dans la table users
        $stmt = $this->pdo->prepare("SELECT id, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            echo json_encode(["error" => "Utilisateur non trouvé"]);
            return;
        }
    
        // 🔹 Mise à jour du rôle avec affichage du nombre de lignes affectées
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$data['role'], $id]);
    
        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Rôle mis à jour avec succès"]);
        } else {
            echo json_encode(["error" => "Aucune mise à jour effectuée"]);
        }

    }




    // Supprimer un utilisateur  
    public function deleteUser($id) {

        $user = new User($this->pdo);
        $user->id = $id;

        if ($user->delete()) {
            echo json_encode(["message" => "Utilisateur supprimé"]);
        } else {
            echo json_encode(["error" => "Échec de la suppression"]);
        }

    }





    // Authentification de l'utilisateur 
    public function login() {

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['email'], $data['password'])) {
            echo json_encode(["error" => "Données incomplètes"]);
            return;
        }
    
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($data['password'], $user['password'])) {
            $jwt = new JwtHandler();
            $token = $jwt->generateToken($user);
    
            echo json_encode([
                "message" => "Connexion réussie",
                "token" => $token,
                "user" => [ "id" => $user['id'], "email" => $user['email'], "pseudo" => $user['pseudo'], "role" => $user['role'] ]
            ]);
        } else {
            echo json_encode(["error" => "Email ou mot de passe incorrect"]);
        }

    }




    // Déconnexion de l'utilisateur 
    public function logout() {

        session_start();
        session_destroy();
        echo json_encode(["message" => "Déconnexion réussie"]);

    }




    // Bonus ajouter crédit manuellement
    public function addCredits($id) {

        $data = json_decode(file_get_contents("php://input"), true);
    
        if (!isset($data['credits']) || $data['credits'] <= 0) {
            echo json_encode(["error" => "Montant invalide"]);
            return;
        }
    
        $query = "UPDATE users SET credits = credits + :credits WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":credits", $data['credits']);
        $stmt->bindParam(":id", $id);
    
        if ($stmt->execute()) {
            echo json_encode(["message" => "Crédits ajoutés avec succès"]);
        } else {
            echo json_encode(["error" => "Erreur lors de l'ajout des crédits"]);
        }

    }

    

    public function getCredits($id) {

        $user = new User($this->pdo);
        $user->id = $id;
    
        $result = $user->getCredits();
        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(["error" => "Utilisateur non trouvé"]);
        }
    }

}
?>


