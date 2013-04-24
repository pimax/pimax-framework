<?php

class BatalineProperty extends BatalineBaseObject
{
    protected $aFields = array(
        "ID", "IBLOCK_ID", "NAME", "ACTIVE", "SORT", "CODE", "DEFAULT_VALUE", "PROPERTY_TYPE", "ROW_COUNT", "COL_COUNT",
        "LIST_TYPE", "MULTIPLE", "XML_ID", "MULTIPLE_CNT", "LINK_IBLOCK_ID", "IS_REQUIRED", "USER_TYPE", "USER_TYPE_SETTINGS",
        "PROPERTY_VALUE_ID", "VALUE", "VALUE_ENUM", "VALUE_XML_ID"
    );

    public function __construct($aData = array())
    {
        if (!is_array($aData)) {
            if ($aData) {
                $aData['Value'] = $aData;
            }
            else {
                $aData = array();
            }
        }
        
        foreach ($aData as $k => $val) {
            $aData[$this->normalizeFieldName($k)] = $val;
        }

        parent::__construct($aData);
    }

    public function getValue()
    {
        // Привязка к элементам ИБ
        if ($this->getPropertyType() == 'E') {
            if (!$this->oDataObject) {

                if ($this->getMultiple() == 'Y') {
                    if (is_array($this->get('Value')) && count($this->get('Value'))) {
                        $aWhere = array(
                            'LOGIC' => 'OR'
                        );
                        foreach ($this->get('Value') as $it) {
                            $aWhere['Id'] = $it;
                        }
                        
                        //echo '<pre>', print_r($this->get('Value'), true), '</pre>';

                        $this->oDataObject = IbFinder::getInstance()->from($this->getLinkIblockId())->where($aWhere)->fetchAll();
                    } else {
                        $this->oDataObject = new BatalineCollection();
                    }
                } else {
                    if ($this->get('Value')) {
                        $this->oDataObject = IbFinder::getInstance()->from($this->getLinkIblockId())->where(array('Id' => $this->get('Value')))->fetch();
                    } else {
                        $this->oDataObject = new BatalineObject();
                    }
                }




            }

            return $this->oDataObject;

        // Привязка к разделам
        } else if ($this->getPropertyType() == 'G') {

            if (!$this->oDataObject) {

                if ($this->getMultiple() == 'Y') {
                    if (is_array($this->get('Value')) && count($this->get('Value'))) {
                        $aWhere = array(
                            'LOGIC' => 'OR'
                        );
                        foreach ($this->get('Value') as $it) {
                            $aWhere['Id'] = $it;
                        }

                        $this->oDataObject = IbSectionFinder::getInstance()->from($this->getLinkIblockId())->where(array($aWhere))->fetchAll();
                    } else {
                        $this->oDataObject = new BatalineCollection();
                    }
                } else {
                    if ($this->get('Value')) {
                        $this->oDataObject = IbSectionFinder::getInstance()->from($this->getLinkIblockId())->where(array('Id' => $this->get('Value')))->fetch();
                    } else {
                        $this->oDataObject = new BatalineSection();
                    }
                }

            }

            return $this->oDataObject;

        // Привязка к пользователю
        } else if ($this->getPropertyType() == 'S' && $this->getUserType() == 'UserID') {

            if (!$this->oDataObject) {

                if ($this->getMultiple() == 'Y') {

                    if (is_array($this->get('Value')) && count($this->get('Value'))) {

                        $aWhere = array(
                            'LOGIC' => 'OR'
                        );
                        foreach ($this->get('Value') as $it) {
                            $aWhere['Id'] = $it;
                        }

                        $this->oDataObject = UserFinder::getInstance()->where(array($aWhere))->fetchAll();
                    } else {

                        $this->oDataObject = new BatalineCollection();
                    }
                } else {
                    if ($this->get('Value')) {
                        $this->oDataObject = UserFinder::getInstance()->where(array('Id' => $this->get('Value')))->fetch();
                    } else {
                        $this->oDataObject = new BatalineUser();
                    }
                }

            }

            return $this->oDataObject;

        // Простое значение
        } else {
            return $this->get('Value');
        }
    }

    public function __toString()
    {
        return $this->getValue();
    }
}