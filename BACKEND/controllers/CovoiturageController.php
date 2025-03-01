<?php
require_once __DIR__ . '/../config/database.php';

class CovoiturageController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }



    // Récupérer tous les covoiturages
    public function getAllCovoiturages() {

        $stmt = $this->pdo->prepare("SELECT * FROM covoiturages ORDER BY date_depart ASC");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

    }




    // Rechercher des covoiturages par ville et date
    public function searchCovoiturages($ville_depart, $date_depart) {

        if (empty($ville_depart) || empty($date_depart)) {
            echo json_encode(["error" => "Paramètres manquants", "ville_depart" => $ville_depart, "date_depart" => $date_depart]);
            return;
        }
    
        $stmt = $this->pdo->prepare("
            SELECT c.id, c.photo, c.nb_places, c.ville_depart, c.ville_arrivee, c.date_depart, c.eco, c.prix, 
                   u.pseudo AS chauffeur_nom, u.note_moyenne
            FROM covoiturages c
            JOIN users u ON c.chauffeur_id = u.id
            WHERE c.ville_depart = ? AND c.date_depart = ?
        ");
        $stmt->execute([$ville_depart, $date_depart]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        echo json_encode($results ?: []);

    }




    // Rechercher covoiturage pour chauffeur
    public function getDriverCovoiturages($driverId) {

        $stmt = $this->pdo->prepare("SELECT id, ville_depart, ville_arrivee, date_depart, prix, nb_places, statut 
                                     FROM covoiturages 
                                     WHERE chauffeur_id = ?");
        $stmt->execute([$driverId]);
        $covoiturages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        echo json_encode($covoiturages);

    }




    // Ajouter un nouveau covoiturage
    public function createCovoiturage($data) {

        $stmt = $this->pdo->prepare("INSERT INTO covoiturages (chauffeur_id, vehicule_id, photo, nb_places, ville_depart, ville_arrivee, date_depart, prix, statut, eco)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['chauffeur_id'], $data['vehicule_id'], $data['photo'], $data['nb_places'],
            $data['ville_depart'], $data['ville_arrivee'], $data['date_depart'], 
            $data['prix'], $data['statut'], $data['eco']
        ]);
        echo json_encode(["message" => "Covoiturage ajouté"]);

    }




    // Mettre à jour un covoiturage
    public function updateCovoiturage($id, $data) {

        $stmt = $this->pdo->prepare("UPDATE covoiturages SET chauffeur_id=?, vehicule_id=?, photo=?, nb_places=?, ville_depart=?, ville_arrivee=?, date_depart=?, prix=?, statut=?, eco=? WHERE id=?");
        $stmt->execute([
            $data['chauffeur_id'], $data['vehicule_id'], $data['photo'], $data['nb_places'],
            $data['ville_depart'], $data['ville_arrivee'], $data['date_depart'], 
            $data['prix'], $data['statut'], $data['eco'], $id
        ]);
        echo json_encode(["message" => "Covoiturage mis à jour"]);

    }




    // Supprimer un covoiturage
    public function deleteCovoiturage($id) {

        $stmt = $this->pdo->prepare("DELETE FROM covoiturages WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["message" => "Covoiturage supprimé"]);

    }


    

    // Anuller un covoiturage (chauffeur) 
    public function cancelCovoiturage($covoiturageId) {
        $stmt = $this->pdo->prepare("UPDATE covoiturages SET statut = 'annulé' WHERE id = ?");
        $stmt->execute([$covoiturageId]);
    
        echo json_encode(["message" => "Covoiturage annulé avec succès"]);
    }

    
}
?>


