<?php

/**
 * IbFinder: Выборка элементов из инфоблоков
 *
 * @package bataline_framework
 * @subpackage core
 * @version 1.0
 */
class IbFinder
{
    protected $mSelect;
    protected $mFrom;
    protected $mCriteria;
    protected $mOrder;
    protected $nLimit;
    protected $nOffset;
    
    protected $sPaginationTitle;

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
            self::$instance = new IbFinder();
        }

        return self::$instance;
    }

    public function select($mFields = '*')
    {
        $this->mSelect = $mFields;

        return $this;
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

    public function limit($nLimit, $nOffset = false)
    {
        $this->nLimit = $nLimit;
        $this->nOffset = $nOffset;

        return $this;
    }
    
    public function pagination($sTitle)
    {
        $this->sPaginationTitle = $sTitle;
        
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
                    
                    if (is_array($dir) && $by == '') {
                        $dir = array_merge(array("LOGIC" => "OR"), $dir);
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

        // Ограничения выборки
        $aPageParams = array();
        $aPageParams['nPageSize'] = 1;
        $aPageParams['iNumPage'] = 1;

        $oResult = false;
        $res = CIBlockElement::GetList($aOrder, $aFilter, false, $aPageParams, $aSelect);
        while($ob = $res->GetNextElement()){
            /// Подготовка массивов к импорту
            $aFields = array();
            $aFieldsOrig = $ob->GetFields();
            if (!empty($aFieldsOrig)) {
                foreach ($aFieldsOrig as $key => $aItem) {
                     $aFields[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            $aProps = array();
            $aPropsOrig = $ob->GetProperties();
            if (!empty($aPropsOrig)) {
                foreach ($aPropsOrig as $key => $aItem) {
                     $aProps[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            /// Создание объекта с этими данными
            $oResult = new BatalineObject($aFields);
            $oResult->importProperties($aProps);
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
        
        if (!isset($aFilter['ACTIVE'])) {
        	$aFilter['ACTIVE'] = 'Y';
        }

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
                    
                    if (is_array($dir) && $by == '') {
                        //&& strpos($by, "><") === false
                        //&& strpos($by, "!><") === false) {
                        $dir = array_merge(array("LOGIC" => "OR"), $dir);
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

        // Ограничения выборки
        $aPageParams = array();
        if ($this->nLimit) {
            //$aPageParams['nTopCount'] = $this->nLimit;
            $aPageParams['nPageSize'] = $this->nLimit;
            $aPageParams['iNumPage'] = $this->nOffset / $this->nLimit + 1;
        } else {
            $aPageParams['nPageSize'] = 10000;
            $aPageParams['iNumPage'] = 1;
        }

        $oResult = new BatalineCollection();
        $res = CIBlockElement::GetList($aOrder, $aFilter, false, $aPageParams, $aSelect);
        if (!$this->nLimit) {
            $res->NavStart(10000);
        } else {
            $res->NavStart($this->nLimit);
        }
        $oResult->setPagination($res->GetPageNavStringEx($navComponentObject, $this->sPaginationTitle, "", "Y"));
        $oResult->setCountPages($res->NavPageCount);
        
        while($ob = $res->GetNextElement()){
            /// Подготовка массивов к импорту
            $aFields = array();
            $aFieldsOrig = $ob->GetFields();
            if (!empty($aFieldsOrig)) {
                foreach ($aFieldsOrig as $key => $aItem) {
                    $aFields[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            $aProps = array();
            $aPropsOrig = $ob->GetProperties();
            if (!empty($aPropsOrig)) {
                foreach ($aPropsOrig as $key => $aItem) {
                    $aProps[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            /// Создание объекта с этими данными
            $oObject = new BatalineObject($aFields);
            $oObject->importProperties($aProps);
            $oResult->add($oObject);
        }
        $this->clear();



        return $oResult;
    }

    public function clear()
    {
        $this->mSelect = array("*");
        $this->mOrder = array('SORT' => 'ASC');
        $this->mFrom = '';
        $this->mCriteria = false;
        $this->nLimit = false;
        $this->nOffset = false;
        $this->sPaginationTitle = 'Элементы';

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
