<?php

global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/install/index.php"));
IncludeModuleLangFile($PathInstall."/install.php");
if(class_exists("pimax_framework")) return;

class pimax_framework extends CModule
{
    var $MODULE_ID = "pimax.framework";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function bataline_framework()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = GetMessage("PIMAX_FRAMEWORK_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("PIMAX_FRAMEWORK_INSTALL_DESCRIPTION");

        $this->PARTNER_NAME = "Pimax Interactive";
        $this->PARTNER_URI = "http://ipimax.ru";
    }

    function DoInstall()
    {
        global $DB, $APPLICATION;

        RegisterModule($this->MODULE_ID);
        return true;
    }

    function DoUninstall()
    {
        global $DB, $APPLICATION;

        UnRegisterModule($this->MODULE_ID);
        return true;
    }
}