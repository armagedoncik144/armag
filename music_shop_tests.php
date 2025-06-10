<?php
// Базовый URL вашего приложения
$baseUrl = 'http://localhost';

// 1. Функция для отправки POST-запросов
function sendPostRequest($url, $data) {
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

// 2. Тестирование поиска произведений ансамбля
function testSongSearch($groupName) {
    global $baseUrl;
    $response = sendPostRequest($baseUrl, ['GroupForSongsSearch' => $groupName]);
    echo "Количество произведений для '{$groupName}':\n";
    echo $response . "\n\n";
}

testSongSearch('ансамбль1');
testSongSearch('Nonexistent Band');
testSongSearch(''); // Пустой запрос

// 3. Тестирование поиска пластинок ансамбля
function testDiskSearch($groupName) {
    global $baseUrl;
    $response = sendPostRequest($baseUrl, ['GroupForDiskSearch' => $groupName]);
    echo "Количество пластинок для '{$groupName}':\n";
    echo $response . "\n\n";
}

testDiskSearch('ансамбль2');
testDiskSearch('Nonexistent Band');

// 4. Тестирование топа продаж
function testTopSales() {
    global $baseUrl;
    $response = sendPostRequest($baseUrl, ['ButtForTopSales' => 'кнопка']);
    echo "Топ продаж пластинок:\n";
    echo $response . "\n\n";
}

testTopSales();

// 5. Тестирование работы с пластинками
function testDiskOperations($diskData) {
    global $baseUrl;
    $response = sendPostRequest($baseUrl, $diskData);
    echo "Результат операции с пластинкой:\n";
    echo $response . "\n\n";
}

// Добавление новой пластинки
testDiskOperations([
    'DiskID' => 12345,
    'GroupIDForDisk' => 1,
    'Maker' => 'Sony Music',
    'Adress' => '123 Music Ave',
    'OptPrice' => 10.99,
    'CassualPrice' => 19.99,
    'MakingDate' => '2023-01-15',
    'YearSell' => 5000,
    'PreYearSell' => 4500,
    'NotSelled' => 200
]);

// Обновление пластинки
testDiskOperations([
    'DiskID' => 12345,
    'YearSell' => 5500
]);


// 6. Тестирование работы с ансамблями
function testGroupOperations($groupData) {
    global $baseUrl;
    $response = sendPostRequest($baseUrl, $groupData);
    echo "Результат операции с ансамблем:\n";
    echo $response . "\n\n";
}

// Добавление нового ансамбля
testGroupOperations([
    'GroupID' => 100,
    'GroupName' => 'Arctic Monkeys',
    'Genre' => 'Indie Rock'
]);

// Обновление ансамбля
testGroupOperations([
    'GroupID' => 100,
    'Genre' => 'Alternative Rock'
]);



// 7. Тестирование топа продаж с тестовыми данными
function testTopSalesWithData() {
    global $baseUrl;
    
    // Добавляем тестовые данные
    sendPostRequest($baseUrl, ['DiskID' => 1001, 'YearSell' => 100]);
    sendPostRequest($baseUrl, ['DiskID' => 1002, 'YearSell' => 500]);
    sendPostRequest($baseUrl, ['DiskID' => 1003, 'YearSell' => 300]);
    
    // Получаем топ продаж
    $response = sendPostRequest($baseUrl, ['ButtForTopSales' => 'кнопка']);
    
    echo "Тест топа продаж с тестовыми данными:\n";
    echo $response . "\n\n";
}

testTopSalesWithData();