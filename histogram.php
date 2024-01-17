<?php
include 'db.php';

$dataHistogram = "SELECT favorites.area_id, dataset.ordinalNumberAdministrationCommittee, 
dataset.typeSport, dataset.address, COUNT(favorites.id) AS quantity 
    FROM favorites JOIN dataset on dataset.id = favorites.area_id
    GROUP BY area_id 
    ORDER BY COUNT(favorites.id) desc LIMIT 3";
$stmt = $mysqli->prepare($dataHistogram);
$stmt->execute();
$result = $stmt->get_result();
$playgrounds = [];
$favoritesCount = [];
$playgroundInfo = [];

$count = 0;
while ($row = $result->fetch_assoc()) {
    $count += 1;
    $id[] = $row['area_id'];
    $playgrounds[] = $count;
    $playgroundInfo[]= $count . ') ' . $row['ordinalNumberAdministrationCommittee'] . '; Вид площадки: ' . $row['typeSport'] . '; Адрес: ' . $row['address'] . '.';
    $favoritesCount[] = $row['quantity'];
}
$responseData = [
    'id' => $id,
    'playgrounds' => $playgrounds,
    'favoritesCount' => $favoritesCount,
    'playgroundInfo' => $playgroundInfo,
];

echo json_encode($responseData);

?>