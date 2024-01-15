<?php
include 'db.php';
require("session.php");

$placeId = $_GET['id'];
$user = $session_user['id'];
$view = $_GET['view'];

$insertQuery = "INSERT INTO favorites (user_id, area_id) VALUES (?, ?)";
$stmt = $mysqli->prepare($insertQuery);
$stmt->bind_param('ii', $user, $placeId);

$stmt->execute();

if (isset($_GET['m']) && $_GET['m'] === 'yes' && $_GET['flag'] === 'one') {
    header('Location: map.php?id=' . $placeId . '&view=' . $view);
} elseif (isset($_GET['m']) && $_GET['m'] === 'yes' && $_GET['flag'] === 'big') {
    header('Location: map.php');
} else {
    header('Location: index.php');
}
exit;

?>