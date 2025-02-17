<?php
require_once '../config/database.php';

class CarController {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    // Ajouter un véhicule
    public function addCar() {

        $data = json_decode(file_get_contents("php://input"), true);
    
        if (!isset($data['user_id'], $data['plaque_immatriculation'], $data['date_immatriculation'], $data['modele'], 
                   $data['marque'], $data['couleur'], $data['nb_places'], $data['fumeur'], $data['animaux'], $data['preferences'], $data['eco'])) {
            echo json_encode(["error" => "Données incomplètes"]);
            return;
        }
        // Vérifier si l'utilisateur a déjà un véhicule
        $stmt = $this->pdo->prepare("SELECT id FROM cars WHERE user_id = ?");
        $stmt->execute([$data['user_id']]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($car) {
            // Mise à jour du véhicule existant
            $stmt = $this->pdo->prepare("UPDATE cars SET 
                plaque_immatriculation = ?, date_immatriculation = ?, modele = ?, marque = ?, couleur = ?, 
                nb_places = ?, fumeur = ?, animaux = ?, preferences = ?, eco = ? 
                WHERE user_id = ?");
            $stmt->execute([
                $data['plaque_immatriculation'], $data['date_immatriculation'], $data['modele'], 
                $data['marque'], $data['couleur'], $data['nb_places'], 
                $data['fumeur'], $data['animaux'], $data['preferences'], $data['eco'],
                $data['user_id']
            ]);
            echo json_encode(["message" => "Véhicule mis à jour"]);
        } 
        
        else {
            // Insertion d'un nouveau véhicule
            $stmt = $this->pdo->prepare("INSERT INTO cars (user_id, plaque_immatriculation, date_immatriculation, modele, marque, couleur, nb_places, fumeur, animaux, preferences, eco) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([
                $data['user_id'], $data['plaque_immatriculation'], $data['date_immatriculation'], $data['modele'], 
                $data['marque'], $data['couleur'], $data['nb_places'], $data['fumeur'], $data['animaux'], $data['preferences'], $data['eco']
            ])) {
                echo json_encode(["message" => "Véhicule ajouté"]);
            } else {
                echo json_encode(["error" => "Erreur lors de l'ajout du véhicule"]);
            }
        }

    }


    // Sélectionner un véhicule
    public function getUserCars($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cars);
    }    
}
?>
