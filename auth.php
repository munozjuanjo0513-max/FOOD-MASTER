<?php
require __DIR__ . '/db.php';

$action = $_GET['action'] ?? 'me';

if ($action === 'me') {
    json_out(['email' => $_SESSION['email'] ?? null]);
}

require_post();

if ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    json_out(['ok' => true]);
}

$d = input();
$email = strtolower(trim($d['email'] ?? ''));
$password = (string)($d['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_out(['error' => 'Correo no válido'], 400);

if ($action === 'register') {
    if (strlen($password) < 6) json_out(['error' => 'La contraseña debe tener al menos 6 caracteres'], 400);
    $st = db()->prepare('SELECT id FROM usuarios WHERE email = ?');
    $st->execute([$email]);
    if ($st->fetch()) json_out(['error' => 'Ese correo ya está registrado'], 409);
    $st = db()->prepare('INSERT INTO usuarios (email, password_hash) VALUES (?, ?)');
    $st->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
    $id = (int)db()->lastInsertId();
} elseif ($action === 'login') {
    $st = db()->prepare('SELECT id, password_hash FROM usuarios WHERE email = ?');
    $st->execute([$email]);
    $u = $st->fetch();
    if (!$u || !password_verify($password, $u['password_hash'])) {
        json_out(['error' => 'Correo o contraseña incorrectos'], 401);
    }
    $id = (int)$u['id'];
} else {
    json_out(['error' => 'Acción no válida'], 400);
}

session_regenerate_id(true);
$_SESSION['user_id'] = $id;
$_SESSION['email'] = $email;
json_out(['email' => $email]);
