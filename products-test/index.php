<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Случайные товары");

$APPLICATION->IncludeComponent(
    "vendor:random.products",
    ".default",
    array(
        "IBLOCK_ID" => "2",
        "SECTION_IDS" => "1,2,3,4",
        "CACHE_TIME" => 3600,
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");