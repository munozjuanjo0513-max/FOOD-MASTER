<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = require_login();
    $d = input();
    $title = trim($d['title'] ?? '');
    $text = trim($d['text'] ?? '');
    if ($title === '' || $text === '') json_out(['error' => 'Añade un título y una opinión'], 400);
    if (mb_strlen($title) > 150 || mb_strlen($text) > 1000) json_out(['error' => 'Texto demasiado largo'], 400);
    $st = db()->prepare('INSERT INTO publicaciones (usuario_id, titulo, texto) VALUES (?, ?, ?)');
    $st->execute([$uid, $title, $text]);
    json_out(['id' => (int)db()->lastInsertId()], 201);
}

$uid = (int)($_SESSION['user_id'] ?? 0);
$st = db()->prepare(
    'SELECT p.id, p.titulo AS title, p.texto AS text, p.categoria AS event,
            (SELECT COUNT(*) FROM likes l WHERE l.publicacion_id = p.id) AS likes,
            EXISTS(SELECT 1 FROM likes l WHERE l.publicacion_id = p.id AND l.usuario_id = ?) AS liked
     FROM publicaciones p ORDER BY p.creado_en DESC, p.id DESC LIMIT 50'
);
$st->execute([$uid]);
$rows = $st->fetchAll();

foreach ($rows as &$r) {
    $r['id'] = (int)$r['id'];
    $r['likes'] = (int)$r['likes'];
    $r['liked'] = (bool)$r['liked'];
}
json_out($rows);
