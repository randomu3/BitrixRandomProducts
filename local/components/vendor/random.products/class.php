<?php
namespace Vendor\Components;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Diag\Debug;
use CEventLog;
use CBitrixComponent;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

Loc::loadMessages(__FILE__);

/**
 * Компонент для вывода списка случайных товаров.
 *
 * Данный компонент выводит список из 10 случайных товаров из указанного инфоблока и секций.
 * Результаты выборки кэшируются для улучшения производительности.
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

        if (!isset($arParams['CACHE_TIME']) || !is_numeric($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        }
        return $arParams;
    }

    /**
     * Основной метод компонента, который выполняет логику выборки и подготовки данных.
     *
     * Вызывает метод GetList из модуля iblock для получения 10 случайных элементов.
     * Результат сохраняется в $this->arResult для использования в шаблоне.
     * Данные кэшируются для уменьшения нагрузки на базу данных.
     *
     * @throws LoaderException Если модуль инфоблоков не установлен или не активен.
     */
    public function executeComponent()
    {
        try {
            if (!Loader::includeModule('iblock')) {
                throw new LoaderException(Loc::getMessage("RANDOM_PRODUCTS_IBLOCK_MODULE_NOT_INSTALLED"));
            }

            $sCacheId = md5(serialize($this->arParams));
            $sCacheDir = '/random_products/' . $this->arParams['IBLOCK_ID'];

            $oCache = \Bitrix\Main\Data\Cache::createInstance();

            if ($oCache->initCache($this->arParams['CACHE_TIME'], $sCacheId, $sCacheDir)) {
                $this->arResult = $oCache->getVars();
            } elseif ($oCache->startDataCache()) {
                $arOrder = ["RAND" => "ASC"];
                $arFilter = [
                    "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                    "ACTIVE_DATE" => "Y",
                    "ACTIVE" => "Y",
                    "SECTION_ID" => $this->arParams['SECTION_IDS'],
                    "INCLUDE_SUBSECTIONS" => "Y"
                ];
                $arSelectFields = ["ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL"];

                $dbResult = \CIBlockElement::GetList($arOrder, $arFilter, false, ["nPageSize" => 10], $arSelectFields);

                $this->arResult['ITEMS'] = [];
                while ($oElement = $dbResult->GetNextElement()) {
                    $arFields = $oElement->GetFields();
                    $this->arResult['ITEMS'][] = $arFields;
                }

                if (empty($this->arResult['ITEMS'])) {
                    $this->arResult['ERROR'] = Loc::getMessage("RANDOM_PRODUCTS_NO_PRODUCTS");
                }

                $oCache->endDataCache($this->arResult);
            }

            $this->includeComponentTemplate();
        } catch (LoaderException $e) {
            $this->arResult['ERROR'] = $e->getMessage();

            CEventLog::Add([
                "SEVERITY" => "ERROR",
                "AUDIT_TYPE_ID" => "RANDOM_PRODUCTS_EXCEPTION",
                "MODULE_ID" => "iblock",
                "DESCRIPTION" => "RandomProductsComponent: " . $e->getMessage(),
            ]);

            $oCache->abortDataCache();

            $this->includeComponentTemplate();
        }
    }
}