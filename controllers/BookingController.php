<?php
require_once __DIR__ . '/../models/Booking.php';

class BookingController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }


    public function getAllBookings($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                b.id AS booking_id,
                c.ville_depart,
                c.ville_arrivee,
                c.date_depart,
                c.prix,
                c.eco,
                u.pseudo AS chauffeur_nom
            FROM bookings b
            JOIN covoiturages c ON b.covoiturage_id = c.id
            JOIN users u ON c.chauffeur_id = u.id
            WHERE b.user_id = ?
        ");
        $stmt->execute([$userId]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($bookings)) {
            echo json_encode(["message" => "Aucune réservation trouvée"]);
            return;
        }
    
        echo json_encode($bookings);
    }


    // Réserver un covoiturage
    public function createBooking() {
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (!isset($data['user_id'], $data['covoiturage_id'])) {
            echo json_encode(["error" => "Données incomplètes"]);
            return;
        }
    
        // Récupérer le prix du covoiturage
        $stmt = $this->pdo->prepare("SELECT prix, nb_places FROM covoiturages WHERE id = ?");
        $stmt->execute([$data['covoiturage_id']]);
        $covoiturage = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$covoiturage || $covoiturage['nb_places'] <= 0) {
            echo json_encode(["error" => "Ce trajet n'est plus disponible ou ne possède plus de place"]);
            return;
        }
    
        // Vérifier les crédits de l'utilisateur
        $stmt = $this->pdo->prepare("SELECT credits FROM users WHERE id = ?");
        $stmt->execute([$data['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user['credits'] < $covoiturage['prix']) {
            echo json_encode(["error" => "Crédits insuffisants"]);
            return;
        }
    
        // Déduire les crédits et réserver la place
        $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, covoiturage_id) VALUES (?, ?)");
        $stmt->execute([$data['user_id'], $data['covoiturage_id']]);
    
        // Mettre à jour les crédits de l'utilisateur
        $stmt = $this->pdo->prepare("UPDATE users SET credits = credits - ? WHERE id = ?");
        $stmt->execute([$covoiturage['prix'], $data['user_id']]);
    
        // Mettre à jour les places disponibles
        $stmt = $this->pdo->prepare("UPDATE covoiturages SET nb_places = nb_places - 1 WHERE id = ?");
        $stmt->execute([$data['covoiturage_id']]);
    
        echo json_encode(["message" => "Réservation réussie"]);
    }


    public function cancelBooking($bookingId) {
        // Récupérer le prix du covoiturage et l'utilisateur concerné
        $stmt = $this->pdo->prepare("
            SELECT b.user_id, c.prix, c.id AS covoiturage_id
            FROM bookings b
            JOIN covoiturages c ON b.covoiturage_id = c.id
            WHERE b.id = ?
        ");
        $stmt->execute([$bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$booking) {
            echo json_encode(["error" => "Réservation introuvable"]);
            return;
        }
    
        $userId = $booking['user_id'];
        $prix = $booking['prix'];
        $covoiturageId = $booking['covoiturage_id'];
    
        // Rendre les crédits à l'utilisateur
        $stmt = $this->pdo->prepare("UPDATE users SET credits = credits + ? WHERE id = ?");
        $stmt->execute([$prix, $userId]);
    
        // Augmenter le nombre de places disponibles dans le covoiturage
        $stmt = $this->pdo->prepare("UPDATE covoiturages SET nb_places = nb_places + 1 WHERE id = ?");
        $stmt->execute([$covoiturageId]);
    
        // Supprimer la réservation
        $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
    
        echo json_encode(["message" => "Réservation annulée avec succès, crédits remboursés"]);
    }


}
?>

