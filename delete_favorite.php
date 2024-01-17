<?php
include 'db.php';
require("session.php");

$favoriteId = $_GET['id'];
$place = $_GET['place'];

$select = "Select area_id FROM favorites WHERE id = ?";
$stmt = $mysqli->prepare($select);
$stmt->bind_param('i', $favoriteId);
$stmt->execute();

$results = $stmt->get_result();

if ($result = $results->fetch_assoc()) {
    $area_id = $result['area_id'];
}


$deleteQuery = "DELETE FROM favorites WHERE id = ?";
$stmt = $mysqli->prepare($deleteQuery);
$stmt->bind_param('i', $favoriteId);

$stmt->execute();

if ($stmt->errno) {
    echo "Error: " . $stmt->error;
} else {
    if (isset($_GET['place']) && $_GET['place'] === 'u' && $_GET['m'] === 'yes'){
        header('Location: user.php');
    } elseif (isset($_GET['m']) && $_GET['m'] === 'no' && $_GET['flag'] === 'one') {
        header('Location: map.php?id='. $area_id . '&place=' . $place);
    } elseif (isset($_GET['m']) && $_GET['m'] === 'no' && $_GET['flag'] === 'big') {
        header('Location: map.php');
    }
    else {
        header('Location: index.php');
    }
    exit;
}

?>
