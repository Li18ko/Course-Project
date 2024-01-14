<?php
include 'db.php';
require("session.php");

$favoriteId = $_GET['id'];

$deleteQuery = "DELETE FROM favorites WHERE id = ?";
$stmt = $mysqli->prepare($deleteQuery);
$stmt->bind_param('i', $favoriteId);

$stmt->execute();

if ($stmt->errno) {
    echo "Error: " . $stmt->error;
} else {
    if (isset($_GET['u']) && $_GET['u'] === 'yes'){
        header('Location: user.php');
    } elseif (isset($_GET['m']) && $_GET['m'] === 'yes') {
        header('Location: map.php');
    }
    else {
        header('Location: index.php');
    }
    exit;
}

?>
