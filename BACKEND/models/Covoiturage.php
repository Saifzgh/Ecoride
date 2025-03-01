<?php
// models/Covoiturage.php

require_once __DIR__ . '/../config/database.php';

class Covoiturage {
    private $conn;
    private $table = "covoiturages";

    public $id;
    public $chauffeur_id;
    public $ville_depart;
    public $ville_arrivee;
    public $date_depart;
    public $prix;
    public $nb_places;
    public $eco;

    public function __construct($db) {
        $this->conn = $db;
    }


    // Créer un nouveau covoiturage
    public function create() {

        $query = "INSERT INTO " . $this->table . " (chauffeur_id, ville_depart, ville_arrivee, date_depart, prix, nb_places, eco)
        VALUES (:chauffeur_id, :ville_depart, :ville_arrivee, :date_depart, :prix, :nb_places, :eco)";;

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":chauffeur_id", $this->chauffeur_id);
        $stmt->bindParam(":ville_depart", $this->ville_depart);
        $stmt->bindParam(":ville_arrivee", $this->ville_arrivee);
        $stmt->bindParam(":date_depart", $this->date_depart);
        $stmt->bindParam(":prix", $this->prix);
        $stmt->bindParam(":nb_places", $this->nb_places);
        $stmt->bindParam(":eco", $this->eco);

        return $stmt->execute();
    }


    // Récupérer tous les covoiturages
    public function readAll() {

        $query = "SELECT * FROM " . $this->table . " WHERE nb_places > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    // Récupérer un covoiturage par ID
    public function readOne() {

        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Mettre à jour un covoiturage
    public function update() {

        $query = "UPDATE " . $this->table . " 
                  SET ville_depart = :ville_depart, ville_arrivee = :ville_arrivee, date_depart = :date_depart, prix = :prix, 
                      nb_places = :nb_places, eco = :eco 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":chauffeur_id", $this->chauffeur_id);
        $stmt->bindParam(":ville_depart", $this->ville_depart);
        $stmt->bindParam(":ville_arrivee", $this->ville_arrivee);
        $stmt->bindParam(":date_depart", $this->date_depart);
        $stmt->bindParam(":prix", $this->prix);
        $stmt->bindParam(":nb_places", $this->nb_places);
        $stmt->bindParam(":eco", $this->eco);

        return $stmt->execute();
    }


    // Supprimer un covoiturage
    public function delete() {
        
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
