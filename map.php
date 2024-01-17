<?php
include 'db.php';
require("session.php");

$place = '';
$flag = 'big';

if (isset($_GET['place'])){
    $place = $_GET['place'];
}

$selectQuery = "SELECT * FROM dataset";
if (isset($_GET['place']) && ($_GET['place'] === 'g' || $_GET['place'] === 'u')){
    $id = $_GET['id'];
    $flag = 'one';
    $selectQuery .= " WHERE id=?";
    $stmt = $mysqli->prepare($selectQuery);
    $stmt->bind_param('i', $id);
} else {
    $stmt = $mysqli->prepare($selectQuery);
}

$stmt->execute();
$areas = $stmt->get_result();

$coordinates = [];

while ($area = $areas->fetch_assoc()) {
    $coordinates[$area['ordinalNumberAdministrationCommittee']] = json_decode($area['coordinates'], true);
    $area_id[$area['ordinalNumberAdministrationCommittee']] = json_decode($area['id'], true);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=15893692-4522-47da-9ead-10f1c46f404d&lang=ru_RU"
        type="text/javascript"></script>

</head>

<body>
    <script type="text/javascript">
        ymaps.ready(function () {
            if (ymaps.Map) {
                init();
            } else {
                console.error('Failed to load Yandex Maps API');
            }
        });

        function init(){
            // Координаты центра Санкт-Петербурга
            const CENTER_COORDINATES = [59.9343, 30.3351];
            // Объект с параметрами центра и уровнем масштабирования карты
            const LOCATION = { center: CENTER_COORDINATES, zoom: 12 }; // Подстраивайте уровень масштабирования

            var myMap = new ymaps.Map('map', LOCATION, {
                controls: ['zoomControl', 'geolocationControl']
            });


            const sportsCoordinates = <?php echo json_encode($coordinates); ?>;
            const area_id = <?php echo json_encode($area_id); ?>;

            const size = Object.keys(sportsCoordinates).length;
          
            var count = 0;

            Object.keys(sportsCoordinates).forEach(sport => {
                var count = count + 1;
                console.log(count);
                const coordinates = sportsCoordinates[sport];
                const ordinalNumberAdministrationCommittee = sport;
                const id_area = area_id[sport];

                const place = <?php echo json_encode($place); ?>;
                const flag = <?php echo json_encode($flag); ?>;
            

                // Создание маркера
                var myPlacemark = new ymaps.Placemark(coordinates, {
                    hintContent: ordinalNumberAdministrationCommittee
                }, {
                    iconLayout: 'default#image',
                    iconImageHref: 'image/location.svg',
                    iconImageSize: [20, 20], // Замените размеры на подходящие для вашего изображения
                    iconImageOffset: [-15, -30] // Смещение метки
                });

                console.log(size);

                if (size === 1){
                    myMap.setCenter(coordinates, 13, { duration: 400 });
                }

                // При клике на метке меняем центр карты на координаты площадки с заданным duration
                myPlacemark.events.add('click', (e) => {
                    const target = e.get('target');
                    myMap.setCenter(target.geometry.getCoordinates(), 13, { duration: 400 });
                });

                // Обработчик клика по титульнику (балуну)
                myPlacemark.events.add('click', (e) => {
                    const target = e.get('target');
                    const modalId = area_id[sport];

                    // Отправка AJAX-запроса на сервер с modalId
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'get_modal_content.php?modalId=' + modalId + '&place=' + place + '&flag=' + flag + '&m=no', true);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            var responseData = JSON.parse(xhr.responseText);
                            var modalContentArray = responseData.modalContentArray;

                            // Отображение контента в модальном окне
                            var modalContent = document.getElementById('modalContent');
                            modalContent.innerHTML = '';

                            modalContentArray.forEach(function (content) {
                                var p = document.createElement('p');
                                p.innerHTML = content;
                                modalContent.appendChild(p);
                            });

                            // Открытие модального окна
                            document.getElementById('myModal').style.display = 'block';

                            // Получение кнопки "close"
                            var closeBtn = document.getElementsByClassName('close')[0];

                            // Добавление обработчика события для закрытия модального окна
                            closeBtn.onclick = function () {
                                document.getElementById('myModal').style.display = 'none';
                            };
                            
                        }
                    };

                    xhr.send();
                });

                // Добавление метки на карту
                myMap.geoObjects.add(myPlacemark);
            }); 
        }
            
    </script>

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
                <?php
                    if (isset($_GET['place']) && ($_GET['place'] === 'u')) {
                ?>
                    <a href="user.php">
                    <img src="image\user.svg" alt="Домой">
                </a><?php
                } else { ?>
                    <a href="index.php">
                        <img src="image\home.svg" alt="На главную">
                    </a><?php
                }?>
            </div>
        </div>
    </header>
    <main>
        <div id="map" class="w-100" style="height: 69vh;"></div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="modalContent"></div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2023-2024 Корнеева Е.С.</p>
    </footer>
</body>

</html>