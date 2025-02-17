<?php
/* PAS ENCORE INITIER

require_once __DIR__ . '/../models/Review.php';

class ReviewController {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new Review();
    }


    public function submitReview() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['covoiturage_id'], $data['user_id'], $data['driver_id'], $data['rating'], $data['comment'])) {
            echo json_encode(["error" => "Données incomplètes"]);
            return;
        }

        $this->reviewModel->addReview(
            $data['covoiturage_id'],
            $data['user_id'],
            $data['driver_id'],
            $data['rating'],
            $data['comment']
        );

        echo json_encode(["message" => "Avis ajouté avec succès !"]);
    }

    public function getDriverReviews($driver_id) {
        $reviews = $this->reviewModel->getReviewsByDriver($driver_id);
        echo json_encode($reviews);
    }


    public function getDriverAverageRating($driver_id) {
        $averageRating = $this->reviewModel->getAverageRating($driver_id);
        echo json_encode(["average_rating" => $averageRating]);
    }
} 

*/

?>
