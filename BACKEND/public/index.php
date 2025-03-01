<?php

// Autoriser toutes les requêtes Cross-Origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Chargement des fichiers nécessaires
require '../vendor/autoload.php';
require '../config/database.php';
require_once '../middlewares/AuthMiddleware.php';
require_once '../controllers/UserController.php';
require_once '../controllers/CovoiturageController.php';
require_once '../controllers/BookingController.php';
require_once '../controllers/ReviewController.php';
require_once '../controllers/AdminController.php';
require_once '../controllers/CarController.php'; 

// Récupération de la requête HTTP
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$uri = preg_replace('/\s+/', '', $uri); 
$method = $_SERVER['REQUEST_METHOD'];


header("Content-Type: application/json"); 

// Initialisation des contrôleurs
$userController = new UserController();
$covoiturageController = new CovoiturageController();
$bookingController = new BookingController();
//$reviewController = new ReviewController();
$adminController = new AdminController();
$carController = new CarController();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Gestion des routes
switch (true) {
    
    // Routes utilisateurs
    case $uri === 'users' && $method === 'GET':
        $userController->getUsers();
        break;

    case $uri === 'users' && $method === 'POST':
        $userController->createUser();
        break;

    case preg_match('/^users\/(\d+)$/', $uri, $matches):
        $userId = $matches[1];
        if ($method === 'GET') {
            $userController->getUser($userId);
        } elseif ($method === 'PUT') {
            $userController->updateUser($userId);
        } elseif ($method === 'DELETE') {
            $userController->deleteUser($userId);
        }
        break;

    // Routes authentification
    case $uri === 'login' && $method === 'POST':
        $userController->login();
        break;

    case $uri === 'logout' && $method === 'GET':
        $userController->logout();
        break;

    // Récupérer tous les covoiturages
    case $uri === "covoiturages" && $method === 'GET':
        $covoiturageController->getAllCovoiturages();
        break;

    // Rechercher un covoiturage par ville et date
    case $uri === "covoiturages/search" && $method === 'GET':
        error_log("🔍 Appel à searchCovoiturages avec ville_depart=" . ($_GET['ville_depart'] ?? 'NON DÉFINI') . " et date_depart=" . ($_GET['date_depart'] ?? 'NON DÉFINI'));
        $covoiturageController->searchCovoiturages($_GET['ville_depart'] ?? '', $_GET['date_depart'] ?? '');
        break;

    // Ajouter un covoiturage
    case $uri === "covoiturages" && $method === 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $covoiturageController->createCovoiturage($data);
        break;

    // Mettre à jour un covoiturage
    case preg_match('/^covoiturages\/(\d+)$/', $uri, $matches) && $method === 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $covoiturageController->updateCovoiturage($matches[1], $data);
        break;

    // Supprimer un covoiturage
    case preg_match('/^covoiturages\/(\d+)$/', $uri, $matches) && $method === 'DELETE':
        $covoiturageController->deleteCovoiturage($matches[1]);
        break;
  
    case preg_match('/^covoiturages\/driver\/(\d+)$/', $uri, $matches) && $method === 'GET':
        $covoiturageController->getDriverCovoiturages($matches[1]);
        break;        

       
    case preg_match('/^users\/(\d+)\/cars$/', $uri, $matches) && $method === 'GET':
        $carController->getUserCars($matches[1]);
        break;        

      
    case $uri === 'bookings' && $method === 'POST':
        $bookingController->createBooking();
        break;
    
    case preg_match('/^bookings\/user\/(\d+)$/', $uri, $matches) && $method === 'GET':
        $bookingController->getAllbookings($matches[1]);
        break;
    
    case preg_match('/^bookings\/(\d+)$/', $uri, $matches) && $method === 'DELETE':
        $bookingController->cancelBooking($matches[1]);
        break;

    case preg_match('/^covoiturages\/(\d+)\/cancel$/', $uri, $matches) && $method === 'PUT':
        $covoiturageController->cancelCovoiturage($matches[1]);
        break;        
        
    case preg_match('/^users\/(\d+)\/credits$/', $uri, $matches) && $method === 'GET':
        $userController->getCredits($matches[1]);
        break;
    
    case preg_match('/^users\/(\d+)\/credits$/', $uri, $matches) && $method === 'PUT':
        $userController->addCredits($matches[1]);
        break;
        
    case $uri === 'reviews' && $method === 'POST':
        $reviewController->submitReview();
        break;
    
    case preg_match('/^reviews\/driver\/(\d+)$/', $uri, $matches) && $method === 'GET':
        $reviewController->getDriverReviews($matches[1]);
        break;
    
    case preg_match('/^reviews\/driver\/(\d+)\/rating$/', $uri, $matches) && $method === 'GET':
        $reviewController->getDriverAverageRating($matches[1]);
        break;

    // Inscription utilisateur
    case $uri === 'register' && $method === 'POST':
        $userController->createUser();
        break;    
        
    // Gestion des utilisateurs
    case $uri === 'admin/users' && $method === 'GET':
        $adminController->getAllUsers();
        break;

    // Filtre pour récupérer les employées uniquement    
    case $uri === "employees" && $method === "GET":
        $userController->getEmployees();
        break;        

    case preg_match('/^admin\/users\/(\d+)\/role$/', $uri, $matches) && $method === 'PUT':
        $adminController->updateUserRole($matches[1]);
        break;
        

    case preg_match('/^users\/(\d+)\/role$/', $uri, $matches) && $method === 'PUT':
        $userController->updateUserRole($matches[1]);
        break;

    case preg_match('/^admin\/users\/(\d+)$/', $uri, $matches) && $method === 'DELETE':
        $adminController->deleteUser($matches[1]);
        break;

    // Gestion des trajets
    case $uri === 'admin/covoiturages' && $method === 'GET':
        $adminController->getAllCovoiturages();
        break;

    case preg_match('/^admin\/covoiturages\/(\d+)$/', $uri, $matches) && $method === 'DELETE':
        $adminController->deleteCovoiturage($matches[1]);
        break;
    
    // Protéger l'espace utilisateur
    case preg_match('/^users\/(\d+)$/', $uri, $matches) && $method === 'GET':
        $user = AuthMiddleware::validateToken();
        if ($user->id != $matches[1] && $user->role != "admin") {
            http_response_code(403);
            echo json_encode(["error" => "Accès interdit"]);
            exit;
    }
    $userController->getUser($matches[1]);
    break;

    // Protéger l'espace admin
    case $uri === 'admin/users' && $method === 'GET':
         $user = AuthMiddleware::validateToken();
        if ($user->role !== "admin") {
            http_response_code(403);
            echo json_encode(["error" => "Accès interdit"]);
            exit;
    }
    $adminController->getAllUsers();
    break;
    
    // Ajout de véhicule
    case $uri === 'cars' && $method === 'POST':
        $carController->addCar();
    break;


    // Route par défaut (404)
    default:
    echo json_encode(["error" => "Route non trouvée", "url_reçue" => $uri, "method" => $method]);
    break;
}
?>


