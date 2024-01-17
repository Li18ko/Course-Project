<?php
include 'db.php';
require("session.php");

if(!isset($_SESSION["user"])){
	echo "Укажите идентификатор пользователя.";
	exit;
}
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
            <div class="sign_in-container">
                <div class="user-container_left">
                    <a href="index.php">
                        <img src="image\home.svg" alt="на главную">
                    </a>
                </div>
                <br>
                <br>
                <div class="user-container_right">
                    <a href="logout.php">
                        <img src="image\exit.svg" alt="Выход">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <?php
            $result = mysqli_query($mysqli , "SELECT * FROM users WHERE id=" . $session_user["id"]);
            $user = mysqli_fetch_assoc($result);
            echo '<p class="h">Добро пожаловать в личный кабинет, <strong style="font-weight: bold;">' . $user['login'] . '</strong> !</p>';
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
                    <form action="user.php" method="GET">
                        <input type="hidden" name="html_type" value="search">
                        <div class="one_search">
                            <input type="text" name="search" placeholder="Поиск..."
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <input type="submit" value="Найти">
                        </div>
                    </form>
                <?php
                } else { ?>
                    <form action="user.php" method="GET">
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
            if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_SERVER["REQUEST_METHOD"])) {
                $searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '';

                $whereConditions = array();
                $bindParams = array();
                $bindTypes = "";
                
                $flag_conclusion = 0;
                $totalRecords = 0;

                if (!empty($searchTerm)) {
                    $whereConditions[] = "(ordinalNumberAdministrationCommittee LIKE (?) OR LOWER(typeSport) LIKE LOWER(?) OR LOWER(address) LIKE LOWER(?) OR 
                    LOWER(metro_transportStop) LIKE LOWER(?) OR LOWER(schedule) LIKE LOWER(?) OR LOWER(status) LIKE LOWER(?))";
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
                $sql = "SELECT COUNT(*) AS total FROM dataset JOIN favorites ON dataset.id = favorites.area_id";
                if (!empty($whereConditions)) {
                    $sql .= " WHERE " . implode(" AND ", $whereConditions);
                    $sql .= " AND favorites.user_id =" . $session_user['id'];
                }
                else {
                    $sql .= " WHERE favorites.user_id =" . $session_user['id'];
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
                $sql = "SELECT * FROM dataset JOIN favorites ON dataset.id = favorites.area_id";
                if (!empty($whereConditions)) {
                    $sql .= " WHERE " . implode(" AND ", $whereConditions);
                    $sql .= " AND favorites.user_id =" . $session_user['id'];
                }
                else {
                    $sql .= " WHERE favorites.user_id =" . $session_user['id'];
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
                    $x = "В Избранном была найдена ";
                } elseif (($totalRecords % 10 >= 2 && $totalRecords % 10 <= 4) && ($totalRecords % 100 < 12 || $totalRecords % 100 > 14)) {
                    $pluralForm = "площадки с помощью поисковой строки";
                    $x = "В Избранном были найдены ";
                } elseif ($totalRecords % 10 === 0 || ($totalRecords % 10 >= 5 && $totalRecords % 10 <= 9) || ($totalRecords % 100 >= 11 && $totalRecords % 100 <= 14)) {
                    $pluralForm = "площадок с помощью поисковой строки";
                    $x = "В Избранном было найдено ";
                }
            }elseif ($flag_conclusion === 2) {
                if ($totalRecords % 10 === 1 && $totalRecords % 100 !== 11) {
                    $pluralForm = "площадка с помощью фильтрации";
                    $x = "В Избранном была найдена ";
                } elseif (($totalRecords % 10 >= 2 && $totalRecords % 10 <= 4) && ($totalRecords % 100 < 12 || $totalRecords % 100 > 14)) {
                    $pluralForm = "площадки с помощью фильтрации";
                    $x = "В Избранном были найдены ";
                } elseif ($totalRecords % 10 === 0 || ($totalRecords % 10 >= 5 && $totalRecords % 10 <= 9) || ($totalRecords % 100 >= 11 && $totalRecords % 100 <= 14)) {
                    $pluralForm = "площадок с помощью фильтрации";
                    $x = "В Избранном было найдено ";
                }
            } else {
                if ($totalRecords % 10 === 1 && $totalRecords % 100 !== 11) {
                    $pluralForm = "площадка, добавленная в Избранное";
                    $x = "В данной таблице представлена ";
                } elseif (($totalRecords % 10 >= 2 && $totalRecords % 10 <= 4) && ($totalRecords % 100 < 12 || $totalRecords % 100 > 14)) {
                    $pluralForm = "площадки, добавленные в Избранное";
                    $x = "В данной таблице представлены ";
                } elseif ($totalRecords % 10 === 0 || ($totalRecords % 10 >= 5 && $totalRecords % 10 <= 9) || ($totalRecords % 100 >= 11 && $totalRecords % 100 <= 14)) {
                    $pluralForm = "площадок, добавленных в Избранное";
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
                            $modalId = $row["area_id"];
                            echo '<tr data-modal-id="' . $modalId . '" class="line_table">';
                            echo '<td>' . (!empty($row["ordinalNumberAdministrationCommittee"]) ? $row["ordinalNumberAdministrationCommittee"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["typeSport"]) ? $row["typeSport"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["address"]) ? $row["address"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["metro_transportStop"]) ? $row["metro_transportStop"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["schedule"]) ? $row["schedule"] : '-') . '</td>';
                            echo '<td>' . (!empty($row["status"]) ? $row["status"] : '-') . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <div id="modalContent"></div>
                        </div>
                    </div>
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
        <br>
    </main>

    <footer>
        <p>&copy; 2023-2024 Корнеева Е.С.</p>
    </footer>

    <script src="modal_in_table.js"></script>

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
