<?php

class IbUpdater
{
    protected $sSaveType;
    protected $mIblock;
    protected $nElementId;
    protected $aData;
    
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
            self::$instance = new IbUpdater();
        }

        return self::$instance;
    }
    
    public function create($mIblockType)
    {
        $this->sSaveType = 'create';
        $this->mIblock = $mIblockType;
        
        return $this;
    }

    public function update($mIblockType, $nElementId)
    {
        $this->sSaveType = 'update';
        $this->mIblock = $mIblockType;
        $this->nElementId = $nElementId;
        
        return $this;
    }

    public function import($aData)
    {
        $aRs = array();
        foreach ($aData as $k => $itm) {
            $aRs[$this->reverseNormalizeFieldName($k)] = $itm;
        }
        
        $this->aData = $aRs;
        
        return $this;
    }

    public function save()
    {
        $el = new CIBlockElement;
        
        $aData['PROPERTY_VALUES'] = array();
        foreach ($this->aData as $k => $val) {
            if (strpos($k, 'PROPERTY_') === 0) {
                $aData['PROPERTY_VALUES'][substr($k, 9)] = $val;
            } else {
                $aData[$k] = $val;
            }
        }
        
        if (is_numeric($this->mIblock)) {
            $aData['IBLOCK_ID'] = $this->mIblock;
        } else {
            $aData['IBLOCK_CODE'] = $this->mIblock;
        }
        
        switch ($this->sSaveType) {
            case 'create':
                $nElementId = $el->Add($aData);
                $this->clear();
            break;
        
            case 'update':
                $el->Update($this->nElementId, $aData);
                $nElementId = $this->nElementId;
                $this->clear();
            break;
        
            default:
                return false;
        }
        
        return $nElementId;
    }

    public function delete($mIblockType, $nElementId)
    {
        CIBlockElement::Delete($nElementId);
        
        return true;
    }
    
    protected function clear()
    {
        $this->aData = array();
        $this->sSaveType = '';
        $this->mIblock = '';
        $this->nElementId = 0;
        
        return true;
    }
    
    protected function normalizeFieldName($sField)
    {
        return Bataline::normalizeFieldName($sField);
    }

    protected function reverseNormalizeFieldName($sField)
    {
        return Bataline::reverseNormalizeFieldName($sField);
    }
}
