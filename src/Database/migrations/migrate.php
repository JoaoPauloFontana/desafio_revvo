<?php
require_once __DIR__ . '/../Database.php';

use RevvoApi\Database\Database;

try {
    $db = Database::connect(1);
    $sql = file_get_contents(__DIR__ . '/courses.sql');
    $db->exec($sql);
    echo "Migrations executadas com sucesso.\n";
} catch (Exception $e) {
    die("Migration failed: " . $e->getMessage());
}
