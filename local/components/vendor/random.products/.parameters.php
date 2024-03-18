<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => '',
        ),
        "SECTION_IDS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("SECTION_IDS"),
            "TYPE" => "STRING",
            "DEFAULT" => '',
        ),
        "CACHE_TIME" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CACHE_TIME"),
            "TYPE" => "STRING",
            "DEFAULT" => 3600
        ),
    ),
);