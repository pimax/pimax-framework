<?php

/**
 * BatalineBaseObject
 * 
 * @package bataline.framework
 * @subpackage core
 * @version 1.2
 * @author Max Pinyugin <m.pinyugin@gmail.com>
 */
class BatalineBaseObject
{
    /**
     * Значения свойств
     * 
     * @var array 
     */
    protected $aData;

    /**
     * Свойства
     * 
     * @var array 
     */
    protected $aFields = array();

    /**
     *
     * @var BatalineObject 
     */
    protected $oDataObject = false;

    /**
     * Конструктор
     * 
     * @param array $aData Значения свойств
     */
    public function __construct($aData = array())
    {
        foreach ($this->aFields as $k => $val) {
            $this->aFields[$k] = $this->normalizeFieldName($val);
        }

        $this->import($aData);
    }

    /**
     * Возвращает свойста объекта
     * 
     * @return array
     */
    public function fields()
    {
        return $this->aFields;
    }

    /**
     * Возвращает значения по-умолчанию
     * 
     * @return array
     */
    public function defaultValues()
    {
        return array();
    }

    /**
     * Присваивает новые значения свойствам
     * 
     * @param array $aData Значения свойств
     * @return boolean
     */
    public function import($aData)
    {
        if (is_array($aData) && count($aData)) {
            foreach ($aData as $key => $value) {
                $sName = 'set'.$key;
                call_user_func(array($this, $sName), $value);
            }

            if (count($this->defaultValues())) {
                foreach ($this->defaultValues() as $alias => $val) {
                    //$alias = $this->normalizeFieldName($alias);

                    if (empty($this->aData[$alias])) {
                        $sName = 'set'.$alias;
                        call_user_func(array($this, $sName), $val);
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Экспортирует значения свойств
     * 
     * @return array
     */
    public function export()
    {
        return $this->aData;
    }

    /**
     * Устанавливает значение свойства
     * 
     * @param string $sName Свойство
     * @param mixed $mValue Значение
     * @return boolean
     */
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

    /**
     * Возвращает значение свойства
     * 
     * @param string $sName Свойство
     * @return boolean
     */
    public function get($sName)
    {
        if ($this->isExists($sName)) {
            return $this->aData[$sName];
        }

        return false;
    }

    public function isExists($sName)
    {
        if (isset($this->aData[$sName])) {
            return true;
        }

        return false;
    }

    public function remove($sName)
    {
        if ($this->isExists($sName)) {
            unset($this->aData[$sName]);

            return true;
        }

        return false;
    }

    public function reset()
    {
        $this->aData = array();

        return true;
    }

    public function toArray()
    {
        return $this->export();
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

    public function __set($sName, $mValue)
    {
        return $this->set($sName, $mValue);
    }

    public function __get($sName)
    {
        return $this->get($sName);
    }

    public function __isset($sName)
    {
        return $this->isExists($sName);
    }

    public function __unset($sName)
    {
        return $this->remove($sName);
    }

    public function offsetExists($offset)
    {
        return $this->isExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function current()
    {
        $sName = 'get'.current($this->aData);

        return $this->$sName();
    }

    public function next()
    {
        $sName = 'get'.next($this->aData);

        return $this->$sName();
    }

    public function key()
    {
        return current($this->aData);
    }

    public function valid()
    {
        return (bool) current($this->aData);
    }

    public function rewind()
    {
        reset($this->aData);
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
