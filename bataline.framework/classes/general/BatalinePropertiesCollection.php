<?php


class BatalinePropertiesCollection extends BatalineCollection
{
    public function set($sName, $mValue)
    {
        if (count($this->fields()) == 0) {
            $this->aData[$sName] = $mValue;

            return true;
        } elseif (count($this->fields()) > 0 && array_search($sName, $this->fields()) !== false) {
            $this->aData[$sName] = $mValue;

            return true;
        }

        return false;
    }

    public function get($sName, $aParams = array())
    {
        if ($this->isExists($sName)) {
            return $this->aData[$sName];
        }

        return false;
    }

    public function isExists($sName)
    {
        if (key_exists($sName, $this->aData)) {
            return true;
        }

        return false;
    }

    public function __call($sName, $aParams = array())
    {
        if (strpos($sName, 'get') === 0) {
            $sVar = substr($sName, 3);

            return $this->get($sVar, $aParams);
        } elseif (strpos($sName, 'set') === 0) {
            $sVar = substr($sName, 3);

            return $this->set($sVar, $aParams[0]);
        }

        return false;
    }
}
