<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Covoiturage.php';
require_once __DIR__ . '/../models/Review.php';

class AdminController {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }


    // Voir tous les utilisateurs
    public function getAllUsers() {

        $user = new User($this->pdo);
        $stmt = $user->getAllUsers();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }


    // Modifier le rôle d'un utilisateur
    public function updateUserRole($id) {

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['role'])) {
            echo json_encode(["error" => "Rôle non spécifié"]);
            return;
        }

        $user = new User($this->pdo);
        if ($user->updateUserRole($id, $data['role'])) {
            echo json_encode(["message" => "Rôle mis à jour"]);
        } else {
            echo json_encode(["error" => "Erreur lors de la mise à jour"]);
        }
    }


    // Supprimer un utilisateur
    public function deleteUser($id) {

        $user = new User($this->pdo);
        if ($user->deleteUser($id)) {
            echo json_encode(["message" => "Utilisateur supprimé"]);
        } else {
            echo json_encode(["error" => "Erreur lors de la suppression"]);
        }
    }


    // Voir tous les trajets
    public function getAllCovoiturages() {

        $covoiturage = new Covoiturage($this->pdo);
        $stmt = $covoiturage->readAll();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }


    // Supprimer un covoiturage
    public function deleteCovoiturage($id) {

        $covoiturage = new Covoiturage($this->pdo);
        if ($covoiturage->delete($id)) {
            echo json_encode(["message" => "Covoiturage supprimé"]);
        } else {
            echo json_encode(["error" => "Erreur lors de la suppression"]);
        }
    }

    /* 
    // Voir tous les avis (pas encore initié)
    public function getAllReviews() {

        $review = new Review();
        echo json_encode($review->getReviews());
    }


    // Supprimer un avis (pas encore initié)
    public function deleteReview($id) {

        $review = new Review();
        if ($review->deleteReview($id)) {
            echo json_encode(["message" => "Avis supprimé"]);
        } else {
            echo json_encode(["error" => "Erreur lors de la suppression"]);
        }
    } */
}
?>
