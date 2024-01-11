<?php
include 'db.php';
require("session.php");

$placeId = $_GET['id'];
$user = $session_user['id'];

$insertQuery = "INSERT INTO favorites (user_id, area_id) VALUES (?, ?)";
$stmt = $mysqli->prepare($insertQuery);
$stmt->bind_param('ii', $user, $placeId);

$stmt->execute();

header('Location: index.php?');
exit;

?>
