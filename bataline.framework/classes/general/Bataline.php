<?php

/**
 * Bataline
 * 
 * @package bataline.framework
 * @subpackage core
 * @version 1.2.1
 * @author Max Pinyugin <m.pinyugin@gmail.com>
 */
class Bataline
{ 
    /**
     * Переводит имена свойств из формата FIELD_NAME в формат FieldName
     * 
     * @param string $sField Имя свойства
     * @return string
     */
    public static function normalizeFieldName($sField)
    {
        return str_replace(" ", "", ucwords(strtolower(str_replace("_", " ", $sField))));
    }
    
    /**
     * Переводит имена свойств из формата FieldName в формат FIELD_NAME
     * 
     * @param string $sField Имя свойства
     * @return string
     */
    public static function reverseNormalizeFieldName($sField)
    {
        $sField = strtoupper(str_replace(array("A", "B", "C", "D", "E", "F", "J", "H", "I", "J", "K", "L", "M", "N", "O", "P",
            "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"), array("_A", "_B", "_C", "_D", "_E", "_F", "_J", "_H", "_I", "_J", "_K", "_L", "_M", "_N", "_O", "_P",
            "_Q", "_R", "_S", "_T", "_U", "_V", "_W", "_X", "_Y", "_Z"), $sField));

        if (strpos($sField, "_") === 0) {
            $sField = substr($sField, 1);
        }
        
        if (strpos($sField, "?_") === 0) {
            $sField = "?" . substr($sField, 2);
        }
        
        if (strpos($sField, "<_") === 0) {
            $sField = "<" . substr($sField, 2);
        }
        
        if (strpos($sField, ">_") === 0) {
            $sField = ">" . substr($sField, 2);
        }
        
        if (strpos($sField, "?_") === 0) {
            $sField = "?" . substr($sField, 2);
        }
        
        if (strpos($sField, "._") !== false) {
        	$sField = str_replace("._", ".", $sField);
        } 
        
        if (strpos($sField, "!_") === 0) {
            $sField = "!" . substr($sField, 2);
        }
        
        if (strpos($sField, "=_") === 0) {
            $sField = "=" . substr($sField, 2);
        }

        if (strpos($sField, "%_") === 0) {
            $sField = "%" . substr($sField, 2);
        }
        
        if (strpos($sField, "!%_") === 0) {
            $sField = "!%" . substr($sField, 3);
        }
        
        if (strpos($sField, "<=_") === 0) {
            $sField = "<=" . substr($sField, 3);
        }
        
        if (strpos($sField, ">=_") === 0) {
            $sField = ">=" . substr($sField, 3);
        }
        
        if (strpos($sField, "><_") === 0) {
            $sField = "><" . substr($sField, 3);
        }
        
        if (strpos($sField, "!><_") === 0) {
            $sField = "!><" . substr($sField, 4);
        }
        
        return $sField;
    }
}
