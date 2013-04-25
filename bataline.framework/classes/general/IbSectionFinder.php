<?php

/**
 * IbSectionFinder: Выборка элементов из инфоблоков
 *
 * @package bataline.framework
 * @subpackage core
 * @version 1.0
 */
class IbSectionFinder
{
    protected $mFrom;
    protected $mCriteria;
    protected $mOrder;

    private static $instance = false;

    private function __construct()
    {
        CModule::IncludeModule('iblock');
        $this->clear();
    }

    private function __clone() {}

    public function getInstance()
    {
        if (self::$instance == false) {
            self::$instance = new IbSectionFinder();
        }

        return self::$instance;
    }

    public function from($mFrom)
    {
        $this->mFrom = $mFrom;

        return $this;
    }

    public function criteria($mCriteria)
    {
        $this->mCriteria = $mCriteria;

        return $this;
    }

    public function order($mOrder)
    {
        $this->mOrder = $mOrder;

        return $this;
    }

    public function where($mCriteria)
    {
        return $this->criteria($mCriteria);
    }

    public function fetch()
    {

        $aFilter = array();
        if (is_numeric($this->mFrom)) {
            $aFilter['IBLOCK_ID'] = $this->mFrom;
        } else {
            $aFilter['IBLOCK_CODE'] = $this->mFrom;
        }
        
        $aFilter['CNT_ACTIVE'] = 'Y';

        // Условия выборки
        if ($this->mCriteria) {
            $aCr = array();
            if (is_object($this->mCriteria)) {
                $aCr = $this->mCriteria->toArray();
            } else if (is_array($this->mCriteria)) {
                foreach ($this->mCriteria as $by => $dir) {
                    
                    if (is_bool($dir)) {
                        if ($dir) {
                            $dir = "Y";
                        } else {
                            $dir = "N";
                        }
                    }
                    
                    $aCr[$this->reverseNormalizeFieldName($by)] = $dir;
                }
            }
            $aFilter = array_merge($aFilter, $aCr);
        }

        // Условия сортировки
        $aOrder = array();
        if ($this->mOrder) {
            foreach ($this->mOrder as $by => $dir) {
                $aOrder[$this->reverseNormalizeFieldName($by)] = $dir;
            }
        }

        // Выборка полей
        $aSelect = array();
        if ($this->mSelect) {
            if (is_array($this->mSelect)) {
                $aSelect = $this->mSelect;
            } else {
                $aSelect = array($this->mSelect);
            }

        }


        $oResult = false;
        $res = CIBlockSection::GetList(array('SORT' => 'ASC'), $aFilter);
        while($ob = $res->GetNextElement()){
            /// Подготовка массива к импорту
            $aFields = array();
            $aFieldsOrig = $ob->GetFields();
            if (!empty($aFieldsOrig)) {
                foreach ($aFieldsOrig as $key => $aItem) {
                    $aFields[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            /// Создание объекта с этими данными
            $oResult = new BatalineSection($aFields);
            break;
        }
        $this->clear();

        return $oResult;
    }

    public function fetchAll()
    {
        $aFilter = array();
        if (is_numeric($this->mFrom)) {
            $aFilter['IBLOCK_ID'] = $this->mFrom;
        } else {
            $aFilter['IBLOCK_CODE'] = $this->mFrom;
        }
        
        $aFilter['CNT_ACTIVE'] = 'Y';
        
        // Условия выборки
        if ($this->mCriteria) {
            $aCr = array();
            if (is_object($this->mCriteria)) {
                $aCr = $this->mCriteria->toArray();
            } else if (is_array($this->mCriteria)) {
                foreach ($this->mCriteria as $by => $dir) {
                    
                    if (is_bool($dir)) {
                        if ($dir) {
                            $dir = "Y";
                        } else {
                            $dir = "N";
                        }
                    }
                    
                    $aCr[$this->reverseNormalizeFieldName($by)] = $dir;
                }
            }
            $aFilter = array_merge($aFilter, $aCr);
        }

        // Условия сортировки
        $aOrder = array();
        if ($this->mOrder) {
            foreach ($this->mOrder as $by => $dir) {
                $aOrder[$this->reverseNormalizeFieldName($by)] = $dir;
            }
        }



        $oResult = new BatalineCollection();
        $res = CIBlockSection::GetList($aOrder, $aFilter, true);
        while($ob = $res->GetNextElement()){
            /// Подготовка массива к импорту
            $aFields = array();
            $aFieldsOrig = $ob->GetFields();
            if (!empty($aFieldsOrig)) {
                foreach ($aFieldsOrig as $key => $aItem) {
                    $aFields[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            /// Создание объекта с этими данными
            $oObject = new BatalineSection($aFields);
            $oResult->add($oObject); 
        }
        $this->clear();

        return $oResult;
    }

    public function clear()
    {
        $this->mOrder = array('SORT' => 'ASC');
        $this->mFrom = '';
        $this->mCriteria = false;

        return true;
    }

    protected function normalizeFieldName($sField)
    {
        return Bataline::getInstance()->normalizeFieldName($sField);
    }

    protected function reverseNormalizeFieldName($sField)
    {
        return Bataline::getInstance()->reverseNormalizeFieldName($sField);
    }
}
