<?php
require_once __DIR__ . '/../controllers/ReviewController.php';

$controller = new ReviewController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "Vous devez être connecté pour laisser un avis.";
        exit;
    }
    $controller->submitReview($_POST['ride_id'], $_SESSION['user_id'], $_POST['rating'], $_POST['comment']);
}

?>

<form method="post">
    <input type="hidden" name="ride_id" value="1"> <!-- Remplacez par l'ID réel du trajet -->
    <label>Note :</label>
    <select name="rating">
        <option value="1">1 - Mauvais</option>
        <option value="2">2 - Moyen</option>
        <option value="3">3 - Bien</option>
        <option value="4">4 - Très bien</option>
        <option value="5">5 - Excellent</option>
    </select>
    <label>Commentaire :</label>
    <textarea name="comment" required></textarea>
    <button type="submit">Soumettre</button>
</form>
