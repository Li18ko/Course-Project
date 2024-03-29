<?php
include 'db.php';
require("session.php");

$modalId = $_GET['modalId'];

$place = 'g';
$flag = 'big'; 
$m = 'yes';

if (isset($_GET['place'])) {
    $place = $_GET['place'];
}

if (isset($_GET['flag'])) {
    $flag = $_GET['flag'];
}

if (isset($_GET['m'])) {
    $m = $_GET['m'];
}

$selectQuery = "SELECT * FROM dataset WHERE id = ?";
$stmt = $mysqli->prepare($selectQuery);
$stmt->bind_param('i', $modalId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$modalContentArray = array();

$modalContentArray[] = '<p class="parameters"><strong>Порядковое число администрации и комитета: </strong>' . (!empty($row["ordinalNumberAdministrationCommittee"]) ? $row["ordinalNumberAdministrationCommittee"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Название организации правообладателя: </strong>' . (!empty($row["nameOrganization"]) ? $row["nameOrganization"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Вид активности, вид спорта: </strong>' . (!empty($row["typeSport"]) ? $row["typeSport"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Адрес: </strong>' . (!empty($row["address"]) ? $row["address"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Уточнение адреса: </strong>' . (!empty($row["clarifyingAddress"]) ? $row["clarifyingAddress"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Ближайшая станция метро, остановка общественного транспорта: </strong>' . (!empty($row["metro_transportStop"]) ? $row["metro_transportStop"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Район: </strong>' . (!empty($row["district"]) ? $row["district"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Телефон(ы): </strong>' . (!empty($row["telephone"]) ? $row["telephone"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Адрес сайта или страницы в социальных сетях: </strong>' . (!empty($row["addressSite_pageSocialNetworks"]) ? $row["addressSite_pageSocialNetworks"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Статус: </strong>' . (!empty($row["status"]) ? $row["status"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Время работы: </strong>' . (!empty($row["schedule"]) ? $row["schedule"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Доступность для лиц с нарушениями здоровья: </strong>' . (!empty($row["accessibilityPeopleDisabilities"]) ? $row["accessibilityPeopleDisabilities"] : '-') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Наличие проката инвентаря: </strong>' . (!empty($row["availabilityRentalEquipment"]) && $row["availabilityRentalEquipment"] == "1" ? "Да" : 'Нет' ) . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Наличие услуг инструктора: </strong>' . (!empty($row["availabilityInstructorServices"] ) && $row["availabilityInstructorServices"] == "1" ? "Да" : 'Нет')  . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Наличие помещения для переодевания: </strong>' . (!empty($row["availabilityChangingRoom"]) && $row["availabilityChangingRoom"] == "1" ? "Да" : 'Нет') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Наличие камеры хранения: </strong>' . (!empty($row["availabilityStorageRoom"]) && $row["availabilityStorageRoom"] == "1" ? "Да" : 'Нет') . '</p>';
$modalContentArray[] = '<p class="parameters"><strong>Иные услуги (перечень): </strong>' . (!empty($row["otherServices"]) ? $row["otherServices"] : '-') . '</p>';

if (isset($_SESSION["user"])) {
    $select = "SELECT id FROM favorites WHERE user_id = ? AND area_id = ?";
    $stmt = $mysqli->prepare($select);
    $stmt->bind_param('ii', $session_user['id'], $row["id"]);
    $stmt->execute();

    $favoritesResult = $stmt->get_result();
    $favoriteRow = $favoritesResult->fetch_assoc();

    if ($favoriteRow) {
        $favoriteId = $favoriteRow['id'];
        $modalContentArray[] = '<div class="favorite"><a href="delete_favorite.php?id=' . $favoriteId . '&place=' . $place . '&flag=' . $flag . '&m=' . $m . '">Удалить из избранного</a></div>';
    } else {
        $modalContentArray[] = '<div class="favorite"><a href="insert_favorite.php?id=' . $row["id"] . '&place=' . $place . '&flag=' . $flag . '&m=' . $m . '">Добавить в избранное</a></div>';
    }

    $stmt->close();
}

if ($m == 'yes'){
    $modalContentArray[] = '<div class="favorite"><a href="map.php?id=' . $row["id"] . '&place=' . $place . '&flag=' . $flag . '">Посмотреть на карте</a></div>';
}


$data = array(
    'id' => $modalId,
    'modalContentArray' => $modalContentArray
);

header('Content-Type: application/json');
echo json_encode($data);
?>