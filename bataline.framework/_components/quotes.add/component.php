<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("bataline.framework"))
	return;
  
$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"])<=0)
	$arParams["IBLOCK_TYPE"] = "news";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

$arResult = array(
	'success' => false
);

if (!empty($_POST['com']))
{
	$attr = $_POST['com'];
	$attr['Active'] = "Y";
	$attr['Code'] = time(); 
	$object = new BatalineObject($attr, intval($arParams["IBLOCK_ID"]));

	if ($object->save())
	{
		$arResult['success'] = true;
	}
}


$this->IncludeComponentTemplate();