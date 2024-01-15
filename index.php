<?php
include 'db.php';
require("session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Внутридворовые спортивные площадки Санкт-Петербурга</title>
    <link rel="stylesheet" href="styless.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="icon" href="image\logo.svg" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header>
        <div class="osn_sign_in">
            <div class="osn">
                <div class="logo-container">
                    <img src="image\logo.svg" alt="Логотип">
                </div>
                <h1>Внутридворовые спортивные площадки Санкт-Петербурга</h1>
                <div class="animated-line"></div>
            </div>
            <div class="index-container">
                <div class="index-container_left">
                    <a href="map.php">
                        <img src="image\map.svg" alt="Карта">
                    </a>
                </div>
                <br>
                <br>
                <div class="index-container_right">
                    <?php
                        if (!isset($_SESSION["user"])){ ?>
                            <a href="auth.php">
                                <img src="image\sign_in.svg" alt="Вход">
                            </a>
                        <?php }
                        else { ?>
                            <a href="user.php">
                                <img src="image\user.svg" alt="Пользователь">
                            </a>
                        <?php }
                    ?>
                </div>
            </div>
        </div>
    </header>

    <main>
        <p class="h">Выберите необходимые параметры и найдите подходящую для вас спортивную площадку</p>

        <?php
            if (!isset($_SESSION["user"])){
                ?><p class="small">После авторизации вам станет доступна функция Избранное</p><?php
            }
        ?>

        <div class="search-container">
            <div id="main_menu">
                <?php
                    $searchTabClass = (empty($_GET['html_type']) || (isset($_GET['html_type']) && $_GET['html_type'] == 'search')) ? 'class="selected"' : '';
                    $filterTabClass = (isset($_GET['html_type']) && $_GET['html_type'] == 'filter') ? 'class="selected"' : '';
                ?>

                <a href="?html_type=search" <?php echo $searchTabClass; ?>>Поисковая строка</a>
                <a href="?html_type=filter" <?php echo $filterTabClass; ?>>Фильтры</a>
            </div> 
            <br>

            <?php
                if (!isset($_GET['html_type']) || $_GET['html_type']== 'search' ){ 
                    ?>
                    <form action="index.php" method="GET">
                        <input type="hidden" name="html_type" value="search">
                        <div class="one_search">
                            <input type="text" name="search" placeholder="Поиск..."
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <input type="submit" value="Найти">
                        </div>
                    </form>
                <?php
                } else { ?>
                    <form action="index.php" method="GET">
                        <input type="hidden" name="html_type" value="filter">
                        <div class="two_search">
                            <label for="typeSport">Тип площадки:</label>
                            <select id="typeSport" name="typeSport">
                                <option value="all_type" <?php echo ($_GET['typeSport'] ?? '') === 'all_type' ? 'selected' : ''; ?>>Не выбрано</option>
                                <option value="badminton" <?php echo ($_GET['typeSport'] ?? '') === 'badminton' ? 'selected' : ''; ?>>Бадминтон</option>
                                <option value="basketball" <?php echo ($_GET['typeSport'] ?? '') === 'basketball' ? 'selected' : ''; ?>>Баскетбол</option>
                                <option value="volleyball" <?php echo ($_GET['typeSport'] ?? '') === 'volleyball' ? 'selected' : ''; ?>>Волейбол</option>
                                <option value="workout" <?php echo ($_GET['typeSport'] ?? '') === 'workout' ? 'selected' : ''; ?>>Воркаут</option>
                                <option value="gymnastic" <?php echo ($_GET['typeSport'] ?? '') === 'gymnastic' ? 'selected' : ''; ?>>Гимнастика</option>
                                <option value="gto" <?php echo ($_GET['typeSport'] ?? '') === 'gto' ? 'selected' : ''; ?>>ГТО</option>
                                <option value="mini-football" <?php echo ($_GET['typeSport'] ?? '') === 'mini-football' ? 'selected' : ''; ?>>Мини-футбол</option>
                                <option value="table-tennis" <?php echo ($_GET['typeSport'] ?? '') === 'table-tennis' ? 'selected' : ''; ?>>Настольный теннис</option>
                                <option value="ofp" <?php echo ($_GET['typeSport'] ?? '') === 'ofp' ? 'selected' : ''; ?>>ОФП</option>
                                <option value="obstacle_course" <?php echo ($_GET['typeSport'] ?? '') === 'obstacle_course' ? 'selected' : ''; ?>>Полоса препятствий</option>
                                <option value="roller_hockey" <?php echo ($_GET['typeSport'] ?? '') === 'roller_hockey' ? 'selected' : ''; ?>>Роллерхоккей</option>
                                <option value="streetball" <?php echo ($_GET['typeSport'] ?? '') === 'streetball' ? 'selected' : ''; ?>>Стритбол</option>
                                <option value="tennis" <?php echo ($_GET['typeSport'] ?? '') === 'tennis' ? 'selected' : ''; ?>>Теннис</option>
                                <option value="exercise_equipment" <?php echo ($_GET['typeSport'] ?? '') === 'exercise_equipment' ? 'selected' : ''; ?>>Тренажеры</option>
                                <option value="football" <?php echo ($_GET['typeSport'] ?? '') === 'football' ? 'selected' : ''; ?>>Футбол</option>
                                <option value="hockey" <?php echo ($_GET['typeSport'] ?? '') === 'hockey' ? 'selected' : ''; ?>>Хоккей</option>
                            </select>
                            
                            <label for="metro_transportStop">Ближайшее метро:</label>
                            <select id="metro_transportStop" name="metro_transportStop">
                                <?php $metro = array(
                                    'avtovo' => 'Автово', 'admiralteyskaya' => 'Адмиралтейская', 'academic' => 'Академическая',
                                    'baltic' => 'Балтийская', 'running' => 'Беговая', 'bucharest' => 'Бухарестская',
                                    'vasileostrovskaya' => 'Василеостровская', 'vladimirskaya' => 'Владимирская', 'volkovskay' => 'Волковская',
                                    'vyborgskaya' => 'Выборгская', 'mining institute' => 'Горный институт', 'gorkovskaya' => 'Горьковская',
                                    'gostiny dvor' => 'Гостиный двор', 'grazhdansky prospekt' => 'Гражданский проспект', 'devyatkino' => 'Девяткино',
                                    'dostoevskaya' => 'Достоевская', 'dunayskaya' => 'Дунайская', 'elizarovskaya' => 'Елизаровская',
                                    'starry' => 'Звездная', 'zvenigorodskaya' => 'Звенигородская', 'zenit' => 'Зенит',
                                    'kirovsky zavod' => 'Кировский завод', 'komendantsky prospekt' => 'Комендантский проспект', 'krestovsky island' => 'Крестовский остров',
                                    'kupchino' => 'Купчино', 'ladozhskaya' => 'Ладожская', 'leninsky prospekt' => 'Ленинский проспект',
                                    'forest' => 'Лесная', 'ligovsky prospekt' => 'Лиговский проспект', 'lomonosovskaya' => 'Ломоносовская',
                                    'mayakovskaya' => 'Маяковская', 'international' => 'Международная', 'moskovskaya' => 'Московская',
                                    'moskovskie vorota' => 'Московские ворота', 'narvskaya' => 'Нарвская', 'nevsky prospekt' => 'Невский проспект',
                                    'novocherkasskaya' => 'Новочеркасская', 'obvodny canal' => 'Обводный канал', 'obukhovo' => 'Обухово',
                                    'ozerki' => 'Озерки', 'victory park' => 'Парк Победы', 'parnas' => 'Парнас',
                                    'petrogradskaya' => 'Петроградская', 'pionerskaya' => 'Пионерская', 'alexander nevsky square' => 'Площадь Александра Невского',
                                    'vosstaniya square' => 'Площадь Восстания', 'lenin square' => 'Площадь Ленина', 'courage square' => 'Площадь Мужества',
                                    'polytechnic' => 'Политехническая', 'primorskaya' => 'Приморская', 'proletarian' => 'Пролетарская',
                                    'bolshevik avenue' => 'Проспект Большевиков', 'veterans avenue' => 'Проспект Ветеранов', 'enlightenment avenue' => 'Проспект Просвещения',
                                    'prospect of glory' => 'Проспект Славы', 'pushkinskaya' => 'Пушкинская', 'fishing' => 'Рыбацкое',
                                    'garden' => 'Садовая', 'haymarket square' => 'Сенная площадь', 'spasskaya' => 'Спасская',
                                    'sports' => 'Спортивная', 'old village' => 'Старая Деревня', 'institute of technology' => 'Технологический институт',
                                    'specific' => 'Удельная', 'dybenko street' => 'Улица Дыбенко', 'frunzenskaya' => 'Фрунзенская',
                                    'black river' => 'Черная речка', 'chernyshevskaya' => 'Чернышевская', 'chkalovskaya' => 'Чкаловская',
                                    'shushary' => 'Шушары', 'electrosila' => 'Электросила'
                                    
                                );
                                ?>

                                <option value="all_type" <?php echo ($_GET['metro_transportStop'] ?? '') === 'all_type' ? 'selected' : ''; ?>>Не выбрано</option>

                                <?php
                                foreach ($metro as $key => $value) {
                                    $selected = isset($_GET['metro_transportStop']) && $_GET['metro_transportStop'] === $key ? 'selected' : '';
                                    echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                                }
                                ?>
                            </select>

                            <label for="availabilityRentalEquipment">Наличие проката инвентаря:</label>
                            <select id="availabilityRentalEquipment" name="availabilityRentalEquipment">
                                <option value="all_type" <?php echo ($_GET['availabilityRentalEquipment'] ?? '') === 'all_type' ? 'selected' : ''; ?>>Не выбрано</option>
                                <option value="yes" <?php echo ($_GET['availabilityRentalEquipment'] ?? '') === 'yes' ? 'selected' : ''; ?>>Да</option>
                                <option value="no" <?php echo ($_GET['availabilityRentalEquipment'] ?? '') === 'no' ? 'selected' : ''; ?>>Нет</option>
                            </select>

                            <label for="availabilityInstructorServices">Наличие услуг инструктора:</label>
                            <select id="availabilityInstructorServices" name="availabilityInstructorServices">
                                <option value="all_type" <?php echo ($_GET['availabilityInstructorServices'] ?? '') === 'all_type' ? 'selected' : ''; ?>>Не выбрано</option>
                                <option value="yes" <?php echo ($_GET['availabilityInstructorServices'] ?? '') === 'yes' ? 'selected' : ''; ?>>Да</option>
                                <option value="no" <?php echo ($_GET['availabilityInstructorServices'] ?? '') === 'no' ? 'selected' : ''; ?>>Нет</option>
                            </select>

                            <label for="availabilityChangingRoom">Наличие помещения для переодевания:</label>
                            <select id="availabilityChangingRoom" name="availabilityChangingRoom">
                                <option value="all_type" <?php echo ($_GET['availabilityChangingRoom'] ?? '') === 'all_type' ? 'selected' : ''; ?>>Не выбрано</option>
                                <option value="yes" <?php echo ($_GET['availabilityChangingRoom'] ?? '') === 'yes' ? 'selected' : ''; ?>>Да</option>
                                <option value="no" <?php echo ($_GET['availabilityChangingRoom'] ?? '') === 'no' ? 'selected' : ''; ?>>Нет</option>
                            </select>

                            <label for="availabilityStorageRoom">Наличие камеры хранения:</label>
                            <select id="availabilityStorageRoom" name="availabilityStorageRoom">
                                <option value="all_type" <?php echo ($_GET['availabilityStorageRoom'] ?? '') === 'all_type' ? 'selected' : ''; ?>>Не выбрано</option>
                                <option value="yes" <?php echo ($_GET['availabilityStorageRoom'] ?? '') === 'yes' ? 'selected' : ''; ?>>Да</option>
                                <option value="no" <?php echo ($_GET['availabilityStorageRoom'] ?? '') === 'no' ? 'selected' : ''; ?>>Нет</option>
                            </select>
                        </div>            
                        <input type="submit" value="Найти">
                    </form>
                <?php
                }
            ?>

        </div>

        <div class="info">
        <?php
            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                $searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '';

                $whereConditions = array();
                $bindParams = array();
                $bindTypes = "";

                $flag_conclusion = 0;

                if (!empty($searchTerm)) {
                    $whereConditions[] = "ordinalNumberAdministrationCommittee LIKE (?) OR LOWER(typeSport) LIKE LOWER(?) OR LOWER(address) LIKE LOWER(?) OR 
                    LOWER(metro_transportStop) LIKE LOWER(?) OR LOWER(schedule) LIKE LOWER(?) OR LOWER(status) LIKE LOWER(?)";
                    $flag_conclusion = 1;
                    // Привязываем параметры для поисковой строки
                    $bindParams = array_pad($bindParams, substr_count($whereConditions[0], '?'), $searchTerm);
                    $bindTypes .= str_repeat('s', substr_count($whereConditions[0], '?'));
                }

                $filterOptions = array('typeSport', 'metro_transportStop', 'availabilityRentalEquipment', 'availabilityInstructorServices', 
                    'availabilityChangingRoom', 'availabilityStorageRoom');

                foreach ($filterOptions as $filter) {
                    if (isset($_GET[$filter]) && $_GET[$filter] !== 'all_type') {
                        $whereConditions[] = "LOWER(" . $filter . ") LIKE LOWER(?)";

                        $flag_conclusion = 2;

                        // Привязываем параметры для фильтров
                        $bindParams[] = "%" . translatingFilter($_GET[$filter]) . "%";
                        $bindTypes .= 's';
                    }
                }

                // Собираем SQL-запрос
                $sql = "SELECT COUNT(*) AS total FROM dataset";
                if (!empty($whereConditions)) {
                    $sql .= " WHERE " . implode(" AND ", $whereConditions);
                }

                $stmt = $mysqli->prepare($sql);

                // Привязываем параметры
                if (!empty($bindParams)) {
                    $stmt->bind_param($bindTypes, ...$bindParams);
                }

                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $totalRecords = $row['total'];

                $recordsPerPage = 10;
                $totalPages = ceil($totalRecords / $recordsPerPage);

                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($currentPage - 1) * $recordsPerPage;

                // Собираем SQL-запрос для выборки данных
                $sql = "SELECT * FROM dataset";
                if (!empty($whereConditions)) {
                    $sql .= " WHERE " . implode(" AND ", $whereConditions);
                }
                $sql .= " LIMIT $offset, $recordsPerPage";

                $stmt = $mysqli->prepare($sql);

                // Привязываем параметры
                if (!empty($bindParams)) {
                    $stmt->bind_param($bindTypes, ...$bindParams);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                $modalId = "";
            }


            if ($totalRecords < 0) {
                return "Некорректное значение";
            }

            $pluralForm = "";
            $x = "";

            if ($flag_conclusion === 1){
                if ($totalRecords % 10 === 1 && $totalRecords % 100 !== 11) {
                    $pluralForm = "площадка с помощью поисковой строки";
                    $x = "Была найдена ";
                } elseif (($totalRecords % 10 >= 2 && $totalRecords % 10 <= 4) && ($totalRecords % 100 < 12 || $totalRecords % 100 > 14)) {
                    $pluralForm = "площадки с помощью поисковой строки";
                    $x = "Были найдены ";
                } elseif ($totalRecords % 10 === 0 || ($totalRecords % 10 >= 5 && $totalRecords % 10 <= 9) || ($totalRecords % 100 >= 11 && $totalRecords % 100 <= 14)) {
                    $pluralForm = "площадок с помощью поисковой строки";
                    $x = "Было найдено ";
                }
            }elseif ($flag_conclusion === 2) {
                if ($totalRecords % 10 === 1 && $totalRecords % 100 !== 11) {
                    $pluralForm = "площадка с помощью фильтрации";
                    $x = "Была найдена ";
                } elseif (($totalRecords % 10 >= 2 && $totalRecords % 10 <= 4) && ($totalRecords % 100 < 12 || $totalRecords % 100 > 14)) {
                    $pluralForm = "площадки с помощью фильтрации";
                    $x = "Были найдены ";
                } elseif ($totalRecords % 10 === 0 || ($totalRecords % 10 >= 5 && $totalRecords % 10 <= 9) || ($totalRecords % 100 >= 11 && $totalRecords % 100 <= 14)) {
                    $pluralForm = "площадок с помощью фильтрации";
                    $x = "Было найдено ";
                }
            } else {
                if ($totalRecords % 10 === 1 && $totalRecords % 100 !== 11) {
                    $pluralForm = "площадка";
                    $x = "В данной таблице представлена ";
                } elseif (($totalRecords % 10 >= 2 && $totalRecords % 10 <= 4) && ($totalRecords % 100 < 12 || $totalRecords % 100 > 14)) {
                    $pluralForm = "площадки";
                    $x = "В данной таблице представлены ";
                } elseif ($totalRecords % 10 === 0 || ($totalRecords % 10 >= 5 && $totalRecords % 10 <= 9) || ($totalRecords % 100 >= 11 && $totalRecords % 100 <= 14)) {
                    $pluralForm = "площадок";
                    $x = "В данной таблице представлено ";
                }
            }

            echo '<p style=font-size:20px;">' . $x . ' ' . $totalRecords . ' ' . $pluralForm . '</p>';

            if ($totalRecords != 0) {
                ?>
                <table>
                    <tr class="table_header">
                        <th>Порядковое число администрации и комитета</th>
                        <th>Вид площадки</th>
                        <th>Адрес</th>
                        <th>Метро/остановка общественного транспорта</th>
                        <th>Режим работы</th>
                        <th>Статус площадки</th>
                    </tr>

                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $modalId = "modal" . $row["id"];
                            echo '<tr data-modal-id="' . $modalId . '" class="line_table">';
                            echo '<td>' . (!empty($row["ordinalNumberAdministrationCommittee"]) ? $row["ordinalNumberAdministrationCommittee"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["typeSport"]) ? $row["typeSport"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["address"]) ? $row["address"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["metro_transportStop"]) ? $row["metro_transportStop"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["schedule"]) ? $row["schedule"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["status"]) ? $row["status"] : '-') . '</td>';
                            echo '</tr>';

                            echo '<div id="' . $modalId . '" class="modal">';
                            echo '    <div class="modal-content">';
                            echo '        <span class="close">&times;</span>';
                            echo '        <p class="parameters"><strong>Порядковое число администрации и комитета: </strong>' . (!empty($row["ordinalNumberAdministrationCommittee"]) ? $row["ordinalNumberAdministrationCommittee"] : '-') . '</p>';
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
                            echo '        <p class="parameters"><strong>Наличие проката инвентаря: </strong>' . ($row["availabilityRentalEquipment"] == '1' ? 'Да' : 'Нет') . '</p>';
                            echo '        <p class="parameters"><strong>Наличие услуг инструктора: </strong>' . ($row["availabilityInstructorServices"] == '1' ? 'Да' : 'Нет') . '</p>';
                            echo '        <p class="parameters"><strong>Наличие помещения для переодевания: </strong>' . ($row["availabilityChangingRoom"] == '1' ? 'Да' : 'Нет') . '</p>';
                            echo '        <p class="parameters"><strong>Наличие камеры хранения: </strong>' . ($row["availabilityStorageRoom"] == '1' ? 'Да' : 'Нет') . '</p>';
                            echo '        <p class="parameters"><strong>Иные услуги (перечень): </strong>' . (!empty($row["otherServices"]) ? $row["otherServices"] : '-') . '</p>';
                            if (isset($_SESSION["user"])){
                                $selectQuery = "SELECT id FROM favorites WHERE user_id = ? AND area_id = ?";
                                $stmt = $mysqli->prepare($selectQuery);
                                $stmt->bind_param('ii', $session_user['id'], $row["id"]);
                                $stmt->execute();
                            
                                $favoritesResult = $stmt->get_result();
                                $favoriteRow = $favoritesResult->fetch_assoc();
                                
                                if ($favoriteRow) {
                                    $favoriteId = $favoriteRow['id'];
                                    ?>
                                    <div class="favorite"><?php
                                        echo '<a href="delete_favorite.php?id=' . $favoriteId . '&view=f">Удалить из избранного</a>';?>
                                    </div>
                                <?php } else {
                                    ?>
                                    <div class="favorite"><?php
                                        echo '<a href="insert_favorite.php?id=' . $row["id"] . '">Добавить в избранное</a>';?>
                                    </div>
                                <?php }
                                $stmt->close();
                            }?>
                            <div class="favorite"><?php
                                echo '<a href="map.php?id=' . $row["id"] . '&view=f">Посмотреть на карте</a>';?>
                            </div><?php
                            echo '    </div>';
                            echo '</div>';
                        }
                    }
                    ?>
                </table>
                <br>
                <br>
                <?php
            }
            ?>
        </div>

        <div class="pagination">
            <?php
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $totalPages = ceil($totalRecords / $recordsPerPage);

            $startPage = max(1, $currentPage - 1);
            $endPage = min($totalPages, $currentPage + 1);

            if ($totalPages != 0) {
                $queryString = http_build_query(array_merge($_GET, ['page' => 1]));
                echo '<a class="btn first-page-btn" href="?'.$queryString.'">Первая страница</a>';

                for ($i = $startPage; $i <= $endPage; $i++) {
                    $queryString = http_build_query(array_merge($_GET, ['page' => $i]));
                    echo '<a class="btn ' . ($i == $currentPage ? 'active' : '') . '" href="?'.$queryString.'">' . $i . '</a>';
                }

                $queryString = http_build_query(array_merge($_GET, ['page' => $totalPages]));
                echo '<a class="btn last-page-btn" href="?'.$queryString.'">Последняя страница</a>';
            }
            ?>
        </div>

        <div class="histogram">
            <h2>Самые популярные спортивные площадки</h2>
            <canvas id="playgroundsChart"></canvas>
            <?php
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
            
            $count = 0;
            while ($row = $result->fetch_assoc()) {
                $count += 1;
                $id[] = $row['area_id'];
                $playgrounds[] = $count;
                echo '<p>'. $count . ') ' . $row['ordinalNumberAdministrationCommittee'] . '; Вид площадки: ' . $row['typeSport'] . '; Адрес: ' . $row['address'] .'.</p>';
                $favoritesCount[] = $row['quantity'];
            }

            ?>
            <script>
                var id = <?php echo json_encode($id); ?>;
                var playgrounds = <?php echo json_encode($playgrounds); ?>;
                var favoritesCount = <?php echo json_encode($favoritesCount); ?>;
                var colors = ['rgba(220, 50, 50, 0.4)', 'rgba(172, 61, 200, 0.4)', 'rgba(54, 162, 235, 0.4)', 'rgba(255, 206, 86, 0.4)', 'rgba(176, 229, 158, 0.4)'];
                var borderСolors = ['rgba(220, 50, 50, 1)', 'rgba(172, 61, 200, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(176, 229, 158, 1)'];

                var ctx = document.getElementById('playgroundsChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: playgrounds,
                        datasets: [{
                            label: 'Количество пользователей, которые были заинтересованы данной площадкой',
                            data: favoritesCount,
                            backgroundColor: colors,
                            borderColor: borderСolors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true,
                            },
                            y: {
                                beginAtZero: true,
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }

                });
            </script>

        </div>
        <br>
    </main>

    <footer>
        <p>&copy; 2023-2024 Корнеева Е.С.</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>

<?php
function translatingFilter($text) {
    $transliterationTable = array(
        'all_type' => 'Не выбрано', 'badminton' => 'Бадминтон', 'basketball' => 'Баскетбол', 'volleyball' => 'Волейбол', 'workout' => 'Воркаут', 
        'gymnastic' => 'Гимнастика', 'gto' => 'ГТО', 'mini-football' => 'Мини-футбол', 'table-tennis' => 'Настольный теннис',
        'ofp' => 'ОФП', 'obstacle_course' => 'Полоса препятствий', 'roller_hockey' => 'Роллерхоккей',
        'streetball' => 'Стритбол', 'tennis' => 'Теннис', 'exercise_equipment' => 'Тренажеры', 'football' => 'Футбол',
        'hockey' => 'Хоккей', 'yes' => 'ИСТИНА', 'no' => 'ЛОЖЬ',
        'avtovo' => 'Автово', 'admiralteyskaya' => 'Адмиралтейская', 'academic' => 'Академическая',
        'baltic' => 'Балтийская', 'running' => 'Беговая', 'bucharest' => 'Бухарестская',
        'vasileostrovskaya' => 'Василеостровская', 'vladimirskaya' => 'Владимирская', 'volkovskay' => 'Волковская',
        'vyborgskaya' => 'Выборгская', 'mining institute' => 'Горный институт', 'gorkovskaya' => 'Горьковская',
        'gostiny dvor' => 'Гостиный двор', 'grazhdansky prospekt' => 'Гражданский проспект', 'devyatkino' => 'Девяткино',
        'dostoevskaya' => 'Достоевская', 'dunayskaya' => 'Дунайская', 'elizarovskaya' => 'Елизаровская',
        'starry' => 'Звездная', 'zvenigorodskaya' => 'Звенигородская', 'zenit' => 'Зенит',
        'kirovsky zavod' => 'Кировский завод', 'komendantsky prospekt' => 'Комендантский проспект', 'krestovsky island' => 'Крестовский остров',
        'kupchino' => 'Купчино', 'ladozhskaya' => 'Ладожская', 'leninsky prospekt' => 'Ленинский проспект',
        'forest' => 'Лесная', 'ligovsky prospekt' => 'Лиговский проспект', 'lomonosovskaya' => 'Ломоносовская',
        'mayakovskaya' => 'Маяковская', 'international' => 'Международная', 'moskovskaya' => 'Московская',
        'moskovskie vorota' => 'Московские ворота', 'narvskaya' => 'Нарвская', 'nevsky prospekt' => 'Невский проспект',
        'novocherkasskaya' => 'Новочеркасская', 'obvodny canal' => 'Обводный канал', 'obukhovo' => 'Обухово',
        'ozerki' => 'Озерки', 'victory park' => 'Парк Победы', 'parnas' => 'Парнас',
        'petrogradskaya' => 'Петроградская', 'pionerskaya' => 'Пионерская', 'alexander nevsky square' => 'Площадь Александра Невского',
        'vosstaniya square' => 'Площадь Восстания', 'lenin square' => 'Площадь Ленина', 'courage square' => 'Площадь Мужества',
        'polytechnic' => 'Политехническая', 'primorskaya' => 'Приморская', 'proletarian' => 'Пролетарская',
        'bolshevik avenue' => 'Проспект Большевиков', 'veterans avenue' => 'Проспект Ветеранов', 'enlightenment avenue' => 'Проспект Просвещения',
        'prospect of glory' => 'Проспект Славы', 'pushkinskaya' => 'Пушкинская', 'fishing' => 'Рыбацкое',
        'garden' => 'Садовая', 'haymarket square' => 'Сенная площадь', 'spasskaya' => 'Спасская',
        'sports' => 'Спортивная', 'old village' => 'Старая Деревня', 'institute of technology' => 'Технологический институт',
        'specific' => 'Удельная', 'dybenko street' => 'Улица Дыбенко', 'frunzenskaya' => 'Фрунзенская',
        'black river' => 'Черная речка', 'chernyshevskaya' => 'Чернышевская', 'chkalovskaya' => 'Чкаловская',
        'shushary' => 'Шушары', 'electrosila' => 'Электросила'
    );

    $text = mb_strtolower($text, 'UTF-8');
    
    $text = str_replace(array_keys($transliterationTable), $transliterationTable, $text);

    return $text;
}
?>