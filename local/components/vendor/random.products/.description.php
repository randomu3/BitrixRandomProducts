<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
    "NAME" => Loc::getMessage('RANDOM_PRODUCTS_NAME'),
    "DESCRIPTION" => Loc::getMessage('RANDOM_PRODUCTS_DESCRIPTION'),
    "PATH" => array(
        "ID" => Loc::getMessage('RANDOM_PRODUCTS_PATH_ID'),
        "CHILD" => array(
            "ID" => "products",
            "NAME" => Loc::getMessage('RANDOM_PRODUCTS_PATH_NAME')
        )
    )
);