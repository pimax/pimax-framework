<?php

class PimaxCollection implements Iterator, Countable, ArrayAccess
{
    protected $aData = array();

    protected $sPagination = false;
    
    protected $nCountPages = false;

    public function __construct($aData = array())
    {
        $this->import($aData);
    }

    public function setPagination($res)
    {
        $this->sPagination = $res;
        return true;
    }

    public function getPagination()
    {
        return $this->sPagination;
    }

    public function import($aData)
    {
        if (is_array($aData) && count($aData)) {
            foreach ($aData as $k => $value) {
                if (is_array($value)) {
                    $this->add(new SimpleObject($value), $k);
                } else {
                    $this->add($value, $k);
                }
            }

            return true;
        }

        return false;
    }

    public function export()
    {
        return $this->aData;
    }

    public function toArray()
    {
        return $this->export();
    }
    
    public function toSimpleArray()
    {
        $aResult = array();
        
        foreach ($this->aData as $k => $val) {
            $aResult[] = $val->getId();
        }
        
        return $aResult;
    }

    public function add($mItem, $sKey = false)
    {
        if ($sKey !== false) {
            $this->aData[$sKey] = $mItem;
        } else {
            $this->aData[] = $mItem;
        }

        return true;
    }

    public function get($sKey)
    {
        return $this->offsetGet($sKey);
    }

    public function at($sKey)
    {
        if ($this->exists($sKey)) {
            return $this->aData[$sKey];
        }

        return false;
    }

    public function exists($sKey)
    {
        return $this->offsetExists($sKey);
    }

    public function current()
    {
        return current($this->aData);
    }

    public function next()
    {
        return next($this->aData);
    }

    public function key()
    {
        return key($this->aData);
    }

    public function valid()
    {
        return (bool) current($this->aData);
    }

    public function rewind()
    {
        reset($this->aData);
    }

    public function count()
    {
        return count($this->aData);
    }

    public function offsetExists($offset)
    {
        return isset($this->aData[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->at($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->add($value, $offset);
    }

    public function offsetUnset($offset){}
    
    public function setCountPages($nCount)
    {
        $this->nCountPages = $nCount;
        
        return true;
    }
    
    public function countPages()
    {
        return $this->nCountPages;
    }
}
