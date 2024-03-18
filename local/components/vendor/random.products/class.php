<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;

/**
 * Компонент для вывода списка 10 случайных товаров.
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
        if (!Loader::includeModule('iblock'))
            return;

        $arOrder = Array("RAND" => "ASC");
        $arFilter = Array("IBLOCK_ID" => $this->arParams['IBLOCK_ID'], "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
        $arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");

        $res = CIBlockElement::GetList($arOrder, $arFilter, false, Array("nPageSize" => 10), $arSelect);

        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $this->arResult['ITEMS'][] = $arFields;
        }

        $this->includeComponentTemplate();
    }
}