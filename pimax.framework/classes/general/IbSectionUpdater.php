<?php

class IbSectionUpdater
{
    protected $sSaveType;
    protected $mIblock;
    protected $nSectionId;
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
            self::$instance = new IbSectionUpdater();
        }

        return self::$instance;
    }
    
    public function create($mIblockType)
    {
        $this->sSaveType = 'create';
        $this->mIblock = $mIblockType;
        
        return $this;
    }

    public function update($mIblockType, $nSectionId)
    {
        $this->sSaveType = 'update';
        $this->mIblock = $mIblockType;
        $this->nSectionId = $nSectionId;
        
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
        $el = new CIBlockSection;
        
        foreach ($this->aData as $k => $val) {
            $aData[$k] = $val;
        }
        
        if (is_numeric($this->mIblock)) {
            $aData['IBLOCK_ID'] = $this->mIblock;
        } else {
            $aData['IBLOCK_CODE'] = $this->mIblock;
        }
        
        switch ($this->sSaveType) {
            case 'create':
                $nSectionId = $el->Add($aData);
                $this->clear();
            break;
        
            case 'update':
                $el->Update($this->nSectionId, $aData);
                $nSectionId = $this->nSectionId;
                $this->clear();
            break;
        
            default:
                return false;
        }
        
        return $nSectionId;
    }

    public function delete($mIblockType, $nSectionId)
    {
        CIBlockSection::Delete($nSectionId);
        
        return true;
    }
    
    protected function clear()
    {
        $this->aData = array();
        $this->sSaveType = '';
        $this->mIblock = '';
        $this->nSectionId = 0;
        
        return true;
    }
    
    protected function normalizeFieldName($sField)
    {
        return Pimax::normalizeFieldName($sField);
    }

    protected function reverseNormalizeFieldName($sField)
    {
        return Pimax::reverseNormalizeFieldName($sField);
    }
}
