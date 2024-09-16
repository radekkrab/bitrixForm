<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['title']) && !empty($_POST['category']) && !empty($_POST['type']) && check_bitrix_sessid()) {
    $title = htmlspecialchars($_POST['title']);
    $category = htmlspecialchars($_POST['category']);
    $type = htmlspecialchars($_POST['type']);
    $warehouse = htmlspecialchars($_POST['warehouse'] ?? '');
    $items = $_POST['items'] ?? [];
    $comment = htmlspecialchars($_POST['comment'] ?? '');

    $to = "radxxx7@mail.ru";
    $subject = "Новая заявка: $title";

    $message = "Категория: $category\nВид заявки: $type\nСклад поставки: $warehouse\nСостав заявки:\n" . implode("\n", $items) . "\nКомментарий: $comment";

    $headers = "From: radxxx7@mail.ru\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

    $body = "--boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message . "\r\n";

    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        $fileContent = chunk_split(base64_encode(file_get_contents($_FILES['file']['tmp_name'])));
        $fileName = $_FILES['file']['name'];
        $body .= "--boundary\r\n";
        $body .= "Content-Type: application/octet-stream; name=\"$fileName\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n\r\n";
        $body .= $fileContent . "\r\n";
    }

    $body .= "--boundary--";

    if (mail($to, $subject, $body, $headers)) {
       LocalRedirect($APPLICATION->GetCurPage() . "?success=1");
    } else {
       LocalRedirect($APPLICATION->GetCurPage() . "?error=1");
    }

}

$this->IncludeComponentTemplate();
