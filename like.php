<?php
require __DIR__ . '/db.php';
require_post();

$uid = require_login();
$pid = (int)(input()['post_id'] ?? 0);

$st = db()->prepare('SELECT 1 FROM publicaciones WHERE id = ?');
$st->execute([$pid]);
if (!$st->fetch()) json_out(['error' => 'Publicación no encontrada'], 404);

$st = db()->prepare('DELETE FROM likes WHERE usuario_id = ? AND publicacion_id = ?');
$st->execute([$uid, $pid]);
$liked = $st->rowCount() === 0;   // si no existía, lo agregamos
if ($liked) {
    db()->prepare('INSERT INTO likes (usuario_id, publicacion_id) VALUES (?, ?)')->execute([$uid, $pid]);
}

$st = db()->prepare('SELECT COUNT(*) FROM likes WHERE publicacion_id = ?');
$st->execute([$pid]);
json_out(['likes' => (int)$st->fetchColumn(), 'liked' => $liked]);
