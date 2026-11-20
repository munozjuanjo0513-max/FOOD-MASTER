<?php
require __DIR__ . '/db.php';

$rows = db()->query(
    'SELECT id, nombre AS name, tipo AS type, imagen AS img, descripcion AS `desc`,
            calificacion AS rating, espera_min AS wait, ubicacion AS location
     FROM restaurantes ORDER BY calificacion DESC'
)->fetchAll();

foreach ($rows as &$r) {
    $r['id'] = (int)$r['id'];
    $r['rating'] = (float)$r['rating'];
    $r['wait'] = (int)$r['wait'];
}
json_out($rows);
