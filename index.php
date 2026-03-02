<?php
// index.php — Front Controller / Router
// TourStack | INES Ruhengeri

session_start();

require_once __DIR__ . '/app/controllers/BookingController.php';

$page   = $_GET['page']   ?? 'home';
$action = $_GET['action'] ?? '';

$controller = new BookingController();

// Router (switch/case — separation of concerns maintained)
switch ($page) {
    case 'home':
        require __DIR__ . '/app/views/home/index.php';
        break;

    case 'booking':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            $controller->showForm();
        }
        break;

    case 'dashboard':
        if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateStatus();
        } else {
            $controller->dashboard();
        }
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/app/views/shared/404.php';
        break;
}
