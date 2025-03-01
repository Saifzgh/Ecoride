<?php
require_once __DIR__ . '/../controllers/BookingController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "Vous devez être connecté pour réserver.";
        exit;
    }

    $controller = new BookingController();
    $controller->book($_SESSION['user_id'], $_POST['ride_id']);
}
?>

<form method="post">
    <input type="hidden" name="ride_id" value="1"> <!-- Remplacez par l'ID réel du trajet -->
    <button type="submit">Réserver</button>
</form>
