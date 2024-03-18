<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Случайные товары");

$APPLICATION->IncludeComponent(
    "vendor:random.products",
    ".default",
    array(
        "IBLOCK_ID" => "x", // замените x на ID инфоблока с товарами
        // здесь могут быть другие параметры, если они были добавлены в компонент
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>