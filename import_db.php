<?php
include 'db.php';

$apiKey = '58d7b39098339f013c4d6f0cf8520de964800b52';
$updateInterval = 3600; // Интервал обновления данных в секундах 

// Установка максимального времени выполнения скрипта в 0 (без ограничения)
ini_set('max_execution_time', 0);

function getAllDatasets($apiKey) {
    $url = "http://data.gov.spb.ru/api/v2/datasets/?per_page=100";
    $datasets = [];

    do {
        // Создаем контекст потока с заголовком для передачи токена
        $options = [
            'http' => [
                'header' => "Authorization: Token $apiKey",
            ],
        ];
        $context = stream_context_create($options);

        $response = file_get_contents($url, false, $context);

        if ($response !== false) {
            $data = json_decode($response, true);
            $datasets = array_merge($datasets, $data['results']);
            $url = $data['next']; // Получаем URL следующей страницы
        } else {
            echo 'Не удалось получить список наборов данных с API.';
            return false;
        }
    } while ($url !== null);

    return $datasets;
}

function getLatestVersionData($datasetId, $apiKey) {
    $url = "http://data.gov.spb.ru/api/v2/datasets/{$datasetId}/versions/latest/";

    // Создаем контекст потока с заголовком для передачи токена
    $options = [
        'http' => [
            'header' => "Authorization: Token $apiKey",
        ],
    ];
    $context = stream_context_create($options);

    $response = file_get_contents($url, false, $context);

    if ($response !== false) {
        $versionData = json_decode($response, true);
        return $versionData;
    } else {
        echo 'Не удалось получить данные о последней версии.';
        return false;
    }
}

function fetchDataFromAPI($datasetId, $apiKey, $latestVersionId, $limit) {
    $url = "http://data.gov.spb.ru/api/v2/datasets/{$datasetId}/versions/latest/data/{$latestVersionId}/?per_page={$limit}";

    // Создаем контекст потока с заголовком для передачи токена
    $options = [
        'http' => [
            'header' => "Authorization: Token $apiKey",
        ],
    ];
    $context = stream_context_create($options);

    // Первый запрос для получения общего количества записей
    $response = file_get_contents($url, false, $context);

    if ($response !== false) {
        $data = json_decode($response, true);
        $totalCount = $data['count'];

        // Общее количество страниц (округленное вверх)
        $totalPages = ceil($totalCount / $limit);

        // Массив для хранения всех данных
        $allResults = [];

        // Цикл для получения данных со всех страниц
        for ($page = 1; $page <= $totalPages; $page++) {
            $pageUrl = "{$url}&page={$page}";
            $pageResponse = file_get_contents($pageUrl, false, $context);

            if ($pageResponse !== false) {
                $pageData = json_decode($pageResponse, true);

                // Преобразование координат в строку перед добавлением в базу данных
                foreach ($pageData['results'] as &$result) {
                    $result['coordinates'] = json_encode($result['coordinates']);
                }

                $allResults = array_merge($allResults, $pageData['results']);
            } else {
                echo 'Не удалось получить данные с API (страница ' . $page . ').';
                return false;
            }
        }

        return $allResults;
    } else {
        echo 'Не удалось получить данные с API.';
        return false;
    }
}

function updateDatabase($mysqli, $data) {
    if (!empty($data)) {
        // Подготовка запроса на вставку
        $stmtInsert = mysqli_prepare($mysqli, "INSERT INTO dataset (ordinalNumberAdministrationCommittee, nameOrganization, typeSport, address, clarifyingAddress, coordinates, metro_transportStop, district, telephone, addressSite_pageSocialNetworks, status, schedule, accessibilityPeopleDisabilities, availabilityRentalEquipment, availabilityInstructorServices, availabilityChangingRoom, availabilityStorageRoom, otherServices) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Подготовка запроса на обновление
        $stmtUpdate = mysqli_prepare($mysqli, "UPDATE dataset SET nameOrganization=?, typeSport=?, address=?, clarifyingAddress=?, coordinates=?, metro_transportStop=?, district=?, telephone=?, addressSite_pageSocialNetworks=?, status=?, schedule=?, accessibilityPeopleDisabilities=?, availabilityRentalEquipment=?, availabilityInstructorServices=?, availabilityChangingRoom=?, availabilityStorageRoom=?, otherServices=? WHERE ordinalNumberAdministrationCommittee=?");

        // Подготовка запроса на удаление
        $stmtDelete = mysqli_prepare($mysqli, "DELETE FROM dataset WHERE ordinalNumberAdministrationCommittee=?");

        // Получение всех существующих записей в базе данных
        $existingRecords = [];
        $result = mysqli_query($mysqli, "SELECT ordinalNumberAdministrationCommittee FROM dataset");
        while ($row = mysqli_fetch_assoc($result)) {
            $existingRecords[] = $row['ordinalNumberAdministrationCommittee'];
        }

        foreach ($data as $item) {
            $rowData = array_values($item);

            // Проверка существования записи в базе данных
            if (in_array($item['number'], $existingRecords)) {
                // Если запись существует, выполнить обновление
                mysqli_stmt_bind_param($stmtUpdate, 'ssssssssssssssssss', $item['name'], $item['activity'], $item['address'],
                $item['addressy'], $item['coordinates'], $item['metro'], $item['area'], $item['phone'], 
                $item['site'], $item['status'], $item['time'], $item['reach'], $item['inventory'], $item['services'], $item['premises'],
                $item['cameras'], $item['others'], $item['number']);

                mysqli_stmt_execute($stmtUpdate);
            } else {
                // Если запись не существует, выполнить вставку
                mysqli_stmt_bind_param($stmtInsert, 'ssssssssssssssssss', $item['number'], $item['name'], $item['activity'], $item['address'],
                $item['addressy'], $item['coordinates'], $item['metro'], $item['area'], $item['phone'], 
                $item['site'], $item['status'], $item['time'], $item['reach'], $item['inventory'], $item['services'], $item['premises'],
                $item['cameras'], $item['others']);

                mysqli_stmt_execute($stmtInsert);
            }
        
        }
        
        // Удаление записей, которых нет в новом датасете, но есть в базе данных
        $missingRecords = array_diff($existingRecords, array_column($data, 'number'));

        foreach ($missingRecords as $missingRecord) {
            mysqli_stmt_bind_param($stmtDelete, 's', $missingRecord);
            mysqli_stmt_execute($stmtDelete);
        }

        mysqli_stmt_close($stmtInsert);
        mysqli_stmt_close($stmtUpdate);
        mysqli_stmt_close($stmtDelete);
    } else {
        echo 'Массив данных для обновления пуст.';
    }
    
}


function exportToCSV($data, $filename) {
    $csvFile = new SplFileObject($filename, 'w');

    // Записываем заголовки столбцов
    $headers = array_keys($data[0]);
    $csvFile->fputcsv($headers);

    foreach ($data as $item) {
        $csvFile->fputcsv($item);
    }

    $csvFile = null;
}

// Получаем все наборы данных
$allDatasets = getAllDatasets($apiKey);

// Если данные успешно получены
if ($allDatasets !== false) {
    // Обработка ошибок и предотвращение вывода данных перед установкой заголовка
    ob_start();

    echo '<pre>';
    print_r($allDatasets);
    echo '</pre>';

    // Проходим по списку наборов данных
    foreach ($allDatasets as $dataset) {
        // Сравниваем имя датасета
        if ($dataset['name'] === 'Внутридворовые спортивные площадки Санкт-Петербурга') {
            $datasetId = $dataset['id'];
            // Получаем id последней версии для данного датасета
            $latestVersionData = getLatestVersionData($datasetId, $apiKey);

            // Если данные успешно получены
            if ($latestVersionData !== false) {
                // Извлекаем необходимое значение "id"
                $latestVersionId = $latestVersionData['structures'][0]['id'];
            } else {
                echo 'Ошибка при получении данных о последней версии.';
            }

            // Запрос данных из API для конкретного датасета
            $data = fetchDataFromAPI($datasetId, $apiKey, $latestVersionId, 100);

            // Если данные успешно получены
            if ($data !== false) {
                // Обработка ошибок и предотвращение вывода данных перед установкой заголовка
                ob_start();

                // Вывод общего массива данных
                echo '<pre>';
                print_r($data);
                echo '</pre>';

                // Вставка данных в базу данных
                updateDatabase($mysqli, $data);

                $query = "CALL SiteTypeColumn()";
                $result = mysqli_query($mysqli, $query);

                // Экспорт данных в CSV
                exportToCSV($data, 'output.csv');

                ob_end_clean(); // Очищаем буфер вывода
            } else {
                echo 'Ошибка при получении данных с API для датасета: ' . $dataset['name'];
            }
        }
    }
} else {
    echo 'Ошибка при получении списка наборов данных с API.';
}

// Ожидание перед следующим обновлением данных
sleep($updateInterval);

// Перенаправление на самого себя для автоматического обновления
header("Location: {$_SERVER['PHP_SELF']}");
exit();
?>
