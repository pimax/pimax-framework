<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("bataline.framework"))
	return;
  
$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"])<=0)
	$arParams["IBLOCK_TYPE"] = "news";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["ELEMENT_ID"] = intval($arParams["ELEMENT_ID"]);

$arResult = array();


$oItem = IbFinder::getInstance()->select('*')
	->from($arParams["IBLOCK_ID"])
	->where(array('Id' => $arParams["ELEMENT_ID"]))
	->fetch();

if ($oItem)
{
	$APPLICATION->SetTitle($oItem->getName());
	$arResult['Item'] = $oItem;
	$this->IncludeComponentTemplate();

} else {
	$APPLICATION->SetTitle("404");
	ShowError("Новость не найдена.");
	@define("ERROR_404", "Y");
	if($arParams["SET_STATUS_404"]==="Y")
		CHTTP::SetStatus("404 Not Found");
}