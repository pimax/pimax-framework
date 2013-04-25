<?php

class BatalineDateTime
{
    protected $nValue = false;
    
    protected $aLebels = array('вчера', 'сегодня', 'завтра');
    
    protected $aMonths = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    
    public function __construct($mValue = false)
    {
        if ($mValue) {
            $this->setValue($mValue);
        }
    }
    
    public function niceDate()
    {
        $sDate = date("d.m.Y", $this->nValue);
        
        if ($sDate == date('d.m.Y')) {
            $sDate = $this->aLebels[1];
        } else if ($sDate == date('d.m.Y', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")))) {
            $sDate = $this->aLebels[0];
        } else if ($sDate == date('d.m.Y', mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")))) {
            $sDate = $this->aLebels[2];
        }
        
        return $sDate;
    }
    
    public function niceDateTime()
    {
        return $this->niceDate().' '.date("H:i", $this->nValue);
    }
    
    public function setValue($mVal)
    {
        if (is_numeric($mVal)) {
            $this->nValue = $mVal;
        } else {
            $this->nValue = strtotime($mVal);
        }
        
        return true;
    }
    
    public function __toString()
    {
        return $this->niceDateTime();
    }
}