<?php
// Configuración de XAMPP por defecto: usuario root, sin contraseña
const DB_HOST = 'localhost';
const DB_NAME = 'foodmaster';
const DB_USER = 'root';
const DB_PASS = '';

session_start();
header('Content-Type: application/json; charset=utf-8');

function json_out($data, int $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            json_out(['error' => 'No se pudo conectar a la base de datos. ¿Está MySQL encendido en XAMPP?'], 500);
        }
    }
    return $pdo;
}

function input(): array {
    $d = json_decode(file_get_contents('php://input'), true);
    return is_array($d) ? $d : [];
}

function require_post() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_out(['error' => 'Método no permitido'], 405);
}

function require_login(): int {
    if (empty($_SESSION['user_id'])) json_out(['error' => 'Debes iniciar sesión'], 401);
    return (int)$_SESSION['user_id'];
}
