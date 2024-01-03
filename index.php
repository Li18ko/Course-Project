<?php

include 'db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ваш заголовок страницы</title>
    <link rel="stylesheet" href="styless.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="icon" href="image\logo.svg" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="image\logo.svg" alt="Логотип">
        </div>
        <h1>Внутридворовые спортивные площадки Санкт-Петербурга</h1>
        <div class="animated-line"></div>
    </header>

    <main>
        <h2>Выберите необходимые параметры и найдите подходящую для вас спортивную площадку</h2>
        <div class="info">
            <table>
                <tr class="table_header">
                    <th>Вид площадки</th>
                    <th>Адрес</th>
                    <th>Метро/остановка общественного транспорта</th>
                    <th>Режим работы</th>
                    <th>Статус площадки</th>
                </tr>
                
                <?php
                $sql = "SELECT * FROM dataset";
                $result = $mysql->query($sql);

                $sql = "SELECT COUNT(*) AS total FROM dataset";
                $result = $mysql->query($sql);
                $row = $result->fetch_assoc();
                $totalRecords = $row['total'];

                $recordsPerPage = 10;
                $totalPages = ceil($totalRecords / $recordsPerPage);

                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($currentPage - 1) * $recordsPerPage;

                $sql = "SELECT * FROM dataset LIMIT $offset, $recordsPerPage";
                $result = $mysql->query($sql);
                $modalId = "";

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $modalId = "modal" . $row["id"];
                        echo '<tr data-modal-id="' . $modalId . '" class="line_table">';
                        echo '<td>' . (!empty($row["typeSport"]) ? $row["typeSport"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["address"]) ? $row["address"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["metro_transportStop"]) ? $row["metro_transportStop"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["schedule"]) ? $row["schedule"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["status"]) ? $row["status"] : '-') . '</td>';
                        echo '</tr>';

                        echo '<div id="' . $modalId . '" class="modal">';
                        echo '    <div class="modal-content">';
                        echo '        <span class="close">&times;</span>';
                        echo '        <p class="parameters"><strong>Название организации правообладателя: </strong>' . (!empty($row["nameOrganization"]) ? $row["nameOrganization"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Вид активности, вид спорта: </strong>' . (!empty($row["typeSport"]) ? $row["typeSport"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Адрес: </strong>' . (!empty($row["address"]) ? $row["address"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Уточнение адреса: </strong>' . (!empty($row["clarifyingAddress"]) ? $row["clarifyingAddress"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Ближайшая станция метро, остановка общественного транспорта: </strong>' . (!empty($row["metro_transportStop"]) ? $row["metro_transportStop"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Район: </strong>' . (!empty($row["district"]) ? $row["district"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Телефон(ы): </strong>' . (!empty($row["telephone"]) ? $row["telephone"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Адрес сайта или страницы в социальных сетях: </strong>' . (!empty($row["addressSite_pageSocialNetworks"]) ? $row["addressSite_pageSocialNetworks"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Статус: </strong>' . (!empty($row["status"]) ? $row["status"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Время работы: </strong>' . (!empty($row["schedule"]) ? $row["schedule"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Доступность для лиц с нарушениями здоровья: </strong>' . (!empty($row["accessibilityPeopleDisabilities"]) ? $row["accessibilityPeopleDisabilities"] : '-') . '</p>';
                        echo '        <p class="parameters"><strong>Наличие проката инвентаря: </strong>' . ($row["availabilityRentalEquipment"] == 'ЛОЖЬ'? 'Нет' : 'Да') . '</p>';
                        echo '        <p class="parameters"><strong>Наличие услуг инструктора: </strong>' . ($row["availabilityInstructorServices"] == 'ЛОЖЬ'? 'Нет' : 'Да') . '</p>';
                        echo '        <p class="parameters"><strong>Наличие помещения для переодевания: </strong>' . ($row["availabilityChangingRoom"] == 'ЛОЖЬ'? 'Нет' : 'Да') . '</p>';
                        echo '        <p class="parameters"><strong>Наличие камеры хранения: </strong>' . ($row["availabilityStorageRoom"] == 'ЛОЖЬ'? 'Нет' : 'Да') . '</p>';
                        echo '        <p class="parameters"><strong>Иные услуги (перечень): </strong>' . (!empty($row["otherServices "]) ? $row["otherServices "] : '-') . '</p>';
                        echo '    </div>';
                        echo '</div>';
                    }
                }
                ?>
            </table>
        </div>

        <div class="pagination">
            <?php
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $totalPages = ceil($totalRecords / $recordsPerPage);

            $startPage = max(1, $currentPage - 1);
            $endPage = min($totalPages, $currentPage + 1);

            echo '<a class="btn first-page-btn" href="?page=1">Первая страница</a>';
            for ($i = $startPage; $i <= $endPage; $i++) {
                echo '<a class="btn ' . ($i == $currentPage ? 'active' : '') . '" href="?page=' . $i . '">' . $i . '</a>';
            }
            echo '<a class="btn last-page-btn" href="?page=' . $totalPages . '">Последняя страница</a>';
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Корнеева Е.С.</p>
    </footer>

    <script src="script.js"></script>

</body>
</html>
