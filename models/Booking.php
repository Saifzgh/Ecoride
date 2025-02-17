<?php

require_once __DIR__ . '/../config/database.php';

class Booking {
    private $conn;
    private $table = "bookings";

    public $id;
    public $user_id;
    public $covoiturage_id;
    public $booking_date;
    

    public function __construct($db) {
        $this->conn = $db;
    }   


    // Ajouter une réservation
    public function create() {

        // Vérifier si des places sont disponibles
        $checkQuery = "SELECT nb_places, prix FROM covoiturages WHERE id = :covoiturage_id";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(":covoiturage_id", $this->covoiturage_id);
        $stmt->execute();
        $covoiturage = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$covoiturage || $covoiturage['nb_places'] <= 0) {
            return ["error" => "Plus de places disponibles"];
        }
    
        // Vérifier si l'utilisateur a assez de crédits
        $userQuery = "SELECT credits FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($userQuery);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user || $user['credits'] < $covoiturage['price']) {
            return ["error" => "Crédits insuffisants"];
        }
    
        // Déduire le prix en crédits
        $newCredits = $user['credits'] - $covoiturage['prix'];
        $updateCreditsQuery = "UPDATE users SET credits = :newCredits WHERE id = :user_id";
        $stmt = $this->conn->prepare($updateCreditsQuery);
        $stmt->bindParam(":newCredits", $newCredits);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
    
        // Insérer la réservation
        $query = "INSERT INTO " . $this->table . " (user_id, covoiturage_id) VALUES (:user_id, :covoiturage_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":covoiturage_id", $this->covoiturage_id);
    
        if ($stmt->execute()) {
            // Mettre à jour le nombre de places disponibles
            $updateSeatsQuery = "UPDATE covoiturages SET nb_places = nb_places - 1 WHERE id = :covoiturage_id";
            $stmt = $this->conn->prepare($updateSeatsQuery);
            $stmt->bindParam(":covoiturage_id", $this->covoiturage_id);
            $stmt->execute();
            return ["message" => "Réservation réussie", "nouveaux_credits" => $newCredits];
        }
    
        return ["error" => "Erreur lors de la réservation"];
    }


    // Récupérer toutes les réservations d'un utilisateur
    public function getUserBookings($userId) {

        $stmt = $this->conn->prepare("
            SELECT 
                b.id, 
                b.user_id, 
                b.covoiturage_id, 
                b.booking_date
            FROM bookings b
            WHERE b.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Annuler une réservation
    public function delete() {

        // Vérifier si la réservation existe
        $checkQuery = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            return ["error" => "Réservation introuvable"];
        }

        // Supprimer la réservation
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            // Réajouter une place au covoiturage
            $updateQuery = "UPDATE covoiturages SET nb_places = nb_places + 1 WHERE id = :covoiturage_id";
            $stmt = $this->conn->prepare($updateQuery);
            $stmt->bindParam(":covoiturage_id", $booking['covoiturage_id']);
            $stmt->execute();
            return ["message" => "Réservation annulée"];
        }
        return ["error" => "Erreur lors de l'annulation"];
    }
}
?>

