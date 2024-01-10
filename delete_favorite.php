<?php
include 'db.php';
require("session.php");

$favoriteId = $_GET['id'];

$deleteQuery = "DELETE FROM favorites WHERE id = ?";
$stmt = $mysql->prepare($deleteQuery);
$stmt->bind_param('i', $favoriteId);

$stmt->execute();

if ($stmt->errno) {
    echo "Error: " . $stmt->error;
} else {
    header('Location: index.php');
    exit;
}

?>
