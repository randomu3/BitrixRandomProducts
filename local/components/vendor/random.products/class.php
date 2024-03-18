<?php
namespace Vendor\Components;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag\Debug;
use CEventLog;
use CBitrixComponent;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

/**
 * Компонент для вывода списка случайных товаров.
 */
class RandomProductsComponent extends CBitrixComponent
{
    /**
     * Подготавливает параметры компонента перед выполнением.
     * 
     * @param array $arParams Массив входящих параметров компонента.
     * @return array Отфильтрованный и подготовленный массив параметров.
     */
    public function onPrepareComponentParams($arParams)
    {
        if ($arParams['SECTION_IDS']) {
            $arParams['SECTION_IDS'] = explode(',', $arParams['SECTION_IDS']);
        }
        return $arParams;
    }

    /**
     * Основной метод компонента, который выполняет логику выборки и подготовки данных.
     *
     * Вызывает метод GetList из модуля iblock для получения 10 случайных элементов.
     * Результат сохраняется в $this->arResult для использования в шаблоне.
     */
    public function executeComponent()
    {
        try {
            if (!Loader::includeModule('iblock')) {
                throw new LoaderException(Loc::getMessage("RANDOM_PRODUCTS_IBLOCK_MODULE_NOT_INSTALLED"));
            }

            $arOrder = ["RAND" => "ASC"];
            $arFilter = [
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                "ACTIVE_DATE" => "Y",
                "ACTIVE" => "Y",
                "SECTION_ID" => $this->arParams['SECTION_IDS'],
                "INCLUDE_SUBSECTIONS" => "Y"
            ];
            $arSelect = ["ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL"];

            $res = \CIBlockElement::GetList($arOrder, $arFilter, false, ["nPageSize" => 10], $arSelect);

            $this->arResult['ITEMS'] = [];
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $this->arResult['ITEMS'][] = $arFields;
            }

            if (empty($this->arResult['ITEMS'])) {
                $this->arResult['ERROR'] = Loc::getMessage("RANDOM_PRODUCTS_NO_PRODUCTS");
            }

            $this->includeComponentTemplate();
        } catch (LoaderException $e) {
            $this->arResult['ERROR'] = $e->getMessage();

            // Добавление записи в журнал событий
            CEventLog::Add([
                "SEVERITY" => "ERROR",
                "AUDIT_TYPE_ID" => "RANDOM_PRODUCTS_EXCEPTION",
                "MODULE_ID" => "iblock",
                "DESCRIPTION" => "RandomProductsComponent: " . $e->getMessage(),
            ]);

            $this->includeComponentTemplate();
        }
    }
}