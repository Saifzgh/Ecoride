<?php
// PAS ENCORE TERMINER

/* 

class Review {
    private $collection;

    public function __construct() {
        $this->collection = $mongoClient->ecoride_reviews->reviews; // 📌 Nom de la base et collection
    }

    //  Ajouter un avis
    public function createReview($user_id, $covoiturage_id, $rating, $comment) {
        $review = [
            "user_id" => $user_id,
            "covoiturage_id" => $covoiturage_id,
            "rating" => (int) $rating,
            "comment" => $comment,
            "created_at" => new MongoDB\BSON\UTCDateTime(time() * 1000)
        ];

        $result = $this->collection->insertOne($review);

        if ($result->getInsertedCount() > 0) {
            return ["message" => "Avis ajouté avec succès", "review_id" => (string) $result->getInsertedId()];
        } else {
            return ["error" => "Erreur lors de l'ajout de l'avis"];
        }
    }

    //  Récupérer tous les avis d'un covoiturage
    public function getReviewsByCovoiturage($covoiturage_id) {
        $cursor = $this->collection->find(["covoiturage_id" => $covoiturage_id]);

        $reviews = [];
        foreach ($cursor as $review) {
            $reviews[] = [
                "id" => (string) $review["_id"],
                "user_id" => $review["user_id"],
                "covoiturage_id" => $review["covoiturage_id"],
                "rating" => $review["rating"],
                "comment" => $review["comment"],
                "created_at" => $review["created_at"]->toDateTime()->format("Y-m-d H:i:s")
            ];
        }

        return $reviews;
    }

    // 🔹 Supprimer un avis par son ID
    public function deleteReview($review_id) {
        $result = $this->collection->deleteOne(["_id" => new MongoDB\BSON\ObjectId($review_id)]);

        if ($result->getDeletedCount() > 0) {
            return ["message" => "Avis supprimé avec succès"];
        } else {
            return ["error" => "Erreur lors de la suppression de l'avis"];
        }
    }
}

*/
?>
