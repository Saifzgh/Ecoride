<?php
require_once __DIR__ . '/../controllers/ReviewController.php';

$controller = new ReviewController();
$ride_id = 1; // À remplacer dynamiquement
$reviews = $controller->getRideReviews($ride_id);
$averageRating = $controller->getRideAverageRating($ride_id);
?>

<h2>Note moyenne : <?= number_format($averageRating, 1) ?> / 5</h2>

<?php if (!empty($reviews)): ?>
    <h3>Avis des passagers :</h3>
    <ul>
        <?php foreach ($reviews as $review): ?>
            <li>
                <strong>Note : <?= $review['rating'] ?>/5</strong>
                <p><?= $review['comment'] ?></p>
                <small>Posté le <?= date('d/m/Y', $review['date']->toDateTime()->getTimestamp()) ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun avis pour ce trajet.</p>
<?php endif; ?>
