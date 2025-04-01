<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\WordFrequencyService;
use App\Controllers\WordFrequencyController;

header('Content-Type: application/json');

$service = new WordFrequencyService();
$controller = new WordFrequencyController($service);

try {
    $response = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $response = $controller->processText($input);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['word'])) {
            $response = $controller->getWordFrequency($_GET['word']);
        } else {
            $response = $controller->getAllFrequencies();
        }
    } else {
        throw new Exception('Method not allowed');
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} 