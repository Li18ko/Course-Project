<?php
include 'db.php';
require("session.php");

$favoriteId = $_GET['id'];
$view = $_GET['view'];

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
    if (isset($_GET['u']) && $_GET['u'] === 'yes'){
        header('Location: user.php');
    } elseif (isset($_GET['m']) && $_GET['m'] === 'yes' && $_GET['flag'] === 'one') {
        header('Location: map.php?id='.$area_id.'&view=' . $view);
    } elseif (isset($_GET['m']) && $_GET['m'] === 'yes' && $_GET['flag'] === 'big') {
        header('Location: map.php');
    }
    else {
        header('Location: index.php');
    }
    exit;
}

?>
