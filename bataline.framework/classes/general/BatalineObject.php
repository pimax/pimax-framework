<?php

class BatalineObject extends BatalineBaseObject
{
    protected $aFields = array(
        "ID", "CODE", "EXTERNAL_ID", "XML_ID", "NAME", "IBLOCK_ID", 
        "IBLOCK_SECTION_ID", "IBLOCK_CODE", "ACTIVE", "DATE_ACTIVE_FROM", 
        "DATE_ACTIVE_TO", "ACTIVE_FROM", "ACTIVE_TO", "SORT", "PREVIEW_PICTURE", 
        "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "DETAIL_PICTURE", "DETAIL_TEXT", 
        "DETAIL_TEXT_TYPE", "SEARCHABLE_CONTENT", "DATE_CREATE", 
        "CREATED_BY", "CREATED_USER_NAME", "TIMESTAMP_X", "MODIFIED_BY", 
        "USER_NAME", "LANG_DIR", "LIST_PAGE_URL", "DETAIL_PAGE_URL",
        "SHOW_COUNTER", "SHOW_COUNTER_START", "WF_COMMENTS", "WF_STATUS_ID", 
        "LOCK_STATUS", "TAGS"
    );

    protected $oProperties;
    
    protected $sIBlock;
    
    protected $sID;
    
    //protected $oAuthor = false;

    public function __construct($aData = array(), $sIBlock = '', $sID = 0)
    {
        parent::__construct($aData);
        
        /// Получение ID инфоблока
        if (is_int($sIBlock))
            $this->sIBlock = $sIBlock;
        elseif (is_string($sIBlock)) {
            CModule::IncludeModule('iblock');
            $res = CIBlock::GetList(Array(), Array("CODE"=>$sIBlock), true);
            if ($arItem = $res->Fetch())
                $this->sIBlock = $arItem['ID'];
        }

        $this->sID = $sID;
    }

    public function set($sName, $mValue)
    {
        if (array_search($sName, $this->fields()) !== false) {
            $this->aData[$sName] = $mValue;
            
            return true;
        } else {
            if (strpos($sName, 'Property') === 0)
                $sName = substr($sName, 8);
            
            $this->setProperty($sName, $mValue);
            
            return true;
        }

        return false;
    }

    public function get($sName, $aParams = array())
    {
        if ($this->isExists($sName)) {
            
            if ($sName == 'DetailPicture' || $sName == 'PreviewPicture') {
                return new BatalinePicture($this->aData[$sName]);
                
            } else if ($sName == 'ActiveFrom' || $sName == 'ActiveTo' 
                || $sName == 'DateActiveFrom' || $sName == 'DateActiveTo'
                || $sName == 'DateCreate' || $sName == 'TimestampX') {
                return new BatalineDateTime($this->aData[$sName]);
                
            } else if ($sName == 'CreatedBy' || $sName == 'ModifiedBy') {
                return new BatalineUser($this->aData[$sName]);
                
            }/* else if ($sName == 'Author') {
                if ($this->oAuthor == false) {
                    $this->oAuthor = UserFinder::getInstance()->where(array('CreatedBy' => $this->aData['CreatedBy']))->fetch();
                }
                return $this->oAuthor;

            }*/ else {
                return $this->aData[$sName];
                
            }
            
        } else if (is_object($this->getProperties()) && $this->getProperties()->exists($sName)) {
            return $this->getProperty($sName);
        }

        return false;
    }

    public function importProperties($aData = array())
    {
        $this->oProperties = new BatalinePropertiesCollection();
        foreach ($aData as $alias => $aValue) {
            $aProp = array();
            if (!empty($aValue)) {
                foreach ($aValue as $key => $aItem) {
                    $aProp[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            $this->oProperties->add(new BatalineProperty($aProp), $this->normalizeFieldName($alias));
        }

        return true;
    }

    public function getProperties()
    {
        return $this->oProperties;
    }

    public function setProperty($sName, $mValue)
    {
        if (!is_object($this->oProperties))
            $this->oProperties = new BatalinePropertiesCollection();

        $this->oProperties->add(new BatalineProperty($mValue), $this->normalizeFieldName($sName));
    }

    public function getProperty($sName)
    {
        return $this->oProperties->get($sName)->getValue();
    }
    
    public function implProperties()
    {
        $aData = $this->export();
        $aProp = $this->getProperties();
        foreach ($aProp as $key => $oProp) {
            $aData['Property'.$key] = $oProp->getValue();
        }
        
        return $aData;
    }
    
    public function save()
    {
        $nElementId = 0;
        
        $aData = $this->implProperties();
        if ((int)$this->sIBlock && !empty($aData))
            if ($this->sID)
                $nElementId = IbUpdater::getInstance()->update($this->sIBlock, $this->sID)->import($aData)->save();
            else
                $nElementId = IbUpdater::getInstance()->create($this->sIBlock)->import($aData)->save();
        
        return $nElementId;
    }
    
    public function delete()
    {
        if ($this->sID)
            IbUpdater::getInstance()->delete('', $this->sID);
    }
}
