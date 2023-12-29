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

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . (!empty($row["typeSport"]) ? $row["typeSport"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["address"]) ? $row["address"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["metro_transportStop"]) ? $row["metro_transportStop"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["schedule"]) ? $row["schedule"] : '-') . '</td>';
                        echo '<td>' . (!empty($row["status"]) ? $row["status"] : '-') . '</td>';
                        echo '</tr>';
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
</body>
</html>
