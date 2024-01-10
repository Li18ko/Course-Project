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
    if (isset($_GET['u']) && $_GET['u'] === 'yes'){
        header('Location: user.php');
    }
    else {
        header('Location: index.php');
    }
    exit;
}

?>
