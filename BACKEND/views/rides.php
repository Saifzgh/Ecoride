<?php
require_once __DIR__ . '/../controllers/RideController.php';

$controller = new RideController();
$rides = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rides = $controller->searchRides($_POST['departure'], $_POST['arrival'], $_POST['date']);
}
?>

<form method="post">
    <input type="text" name="departure" placeholder="Ville de départ" required>
    <input type="text" name="arrival" placeholder="Ville d'arrivée" required>
    <input type="date" name="date" required>
    <button type="submit">Rechercher</button>
</form>

<?php if (!empty($rides)): ?>
    <h2>Résultats :</h2>
    <ul>
        <?php foreach ($rides as $ride): ?>
            <li>
                <?= $ride['departure'] ?> → <?= $ride['arrival'] ?> - <?= $ride['date'] ?> - <?= $ride['price'] ?>€ - Places restantes : <?= $ride['seats'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun covoiturage disponible.</p>
<?php endif; ?>
