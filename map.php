<?php
include 'db.php';
require("session.php");

$selectQuery = "SELECT * FROM dataset";
$stmt = $mysqli->prepare($selectQuery);
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
          
            var count = 0;

            Object.keys(sportsCoordinates).forEach(sport => {
                var count = count + 1;
                console.log(count);
                const coordinates = sportsCoordinates[sport];
                const ordinalNumberAdministrationCommittee = sport;
                const id_area = area_id[sport];
                

                // Создание маркера
                var myPlacemark = new ymaps.Placemark(coordinates, {
                    hintContent: ordinalNumberAdministrationCommittee
                }, {
                    iconLayout: 'default#image',
                    iconImageHref: 'image/location.svg',
                    iconImageSize: [20, 20], // Замените размеры на подходящие для вашего изображения
                    iconImageOffset: [-15, -30] // Смещение метки
                });

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
                    xhr.open('GET', 'get_modal_content.php?modalId=' + modalId, true);

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            var modalContent = xhr.responseText;

                            // Отображение контента в модальном окне
                            document.getElementById('modalContent').innerHTML = modalContent;

                            // Открытие модального окна
                            document.getElementById('myModal').style.display = 'block';
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
                <a href="index.php">
                    <img src="image\home.svg" alt="На главную">
                </a>
            </div>
        </div>
    </header>
    <main>
        <div id="map" class="w-100" style="height: 70vh;"></div>
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

    <script src="script.js"></script>
</body>

</html>