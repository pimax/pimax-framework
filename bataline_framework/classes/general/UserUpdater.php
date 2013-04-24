<?php

class UserUpdater
{
    protected $sSaveType;
    protected $nUserId;
    protected $aData;
    
    private static $instance = false;

    private function __construct()
    {
        $this->clear();
    }

    private function __clone() {}

    public function getInstance()
    {
        if (self::$instance == false) {
            self::$instance = new UserUpdater();
        }

        return self::$instance;
    }
    
    public function create()
    {
        $this->sSaveType = 'create';
        
        return $this;
    }

    public function update($nUserId)
    {
        $this->sSaveType = 'update';
        $this->nUserId = $nUserId;
        
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
        $user = new CUser;
        
        foreach ($this->aData as $k => $val) {
            $aData[$k] = $val;
        }
        
        switch ($this->sSaveType) {
            case 'create':
                $nUserId = $user->Add($aData);
                $this->clear();
            break;
        
            case 'update':
                $user->Update($this->nUserId, $aData);
                $nUserId = $this->nUserId;
                $this->clear();
            break;
        
            default:
                return false;
        }
        
        return $nUserId;
    }

    public function delete($nUserId)
    {
        CUser::Delete($nUserId);
        
        return true;
    }
    
    protected function clear()
    {
        $this->aData = array();
        $this->sSaveType = '';
        $this->nUserId = 0;
        
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
