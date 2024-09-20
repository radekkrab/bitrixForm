<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['title']) && !empty($_POST['category']) && !empty($_POST['type']) && check_bitrix_sessid()) {
    $title = htmlspecialchars($_POST['title']);
    $category = htmlspecialchars($_POST['category']);
    $type = htmlspecialchars($_POST['type']);
    $warehouse = htmlspecialchars($_POST['warehouse'] ?? '');
    $items = $_POST['items'] ?? []; // Обеспечиваем, что $items всегда массив
    $comment = htmlspecialchars($_POST['comment'] ?? '');

    // Формируем строку для "Состав заявки"
    $itemsString = '';
    foreach ($items as $item) {
        if (!empty($item['brand']) || !empty($item['name']) || !empty($item['count']) || !empty($item['packaging']) || !empty($item['client'])) {
            $brand = htmlspecialchars($item['brand'] ?? '');
            $name = htmlspecialchars($item['name'] ?? '');
            $count = htmlspecialchars($item['count'] ?? '');
            $packaging = htmlspecialchars($item['packaging'] ?? '');
            $client = htmlspecialchars($item['client'] ?? '');

            $itemsString .= "Бренд: $brand, Наименование: $name, Количество: $count, Фасовка: $packaging, Клиент: $client\n";
        }
    }

    // Обработка загруженного файла
    $filePath = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '/path/to/upload/directory/'; // Укажите путь к директории для загрузки
        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadDir . $fileName;

        // Перемещаем загруженный файл в указанную директорию
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            // Файл успешно загружен
        } else {
            // Ошибка при загрузке файла
            $filePath = ''; // Сбрасываем путь к файлу в случае ошибки
        }
    }

    $arFields = [
        "Заголовок:" => $title,
        "Категория:" => $category,
        "Вид:" => $type,
        "Склад:" => $warehouse,
        "Состав заявки:" => trim($itemsString),
        "Комментарий:" => $comment,
        "EMAIL" => "radxxx7@mail.ru", // Укажите адрес получателя
    ];

    // Если файл был загружен, добавляем его к полям
    if ($filePath) {
        $arFields["FILE"] = CFile::MakeFileArray($filePath);
    }

    // Отправка письма через CEvent
    if (CEvent::Send("NEW_APPLICATION", SITE_ID, $arFields)) {
        LocalRedirect($APPLICATION->GetCurPage() . "?success=1");
    } else {
        LocalRedirect($APPLICATION->GetCurPage() . "?error=1");
    }
}

$this->IncludeComponentTemplate();
