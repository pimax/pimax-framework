<?php

class PimaxSection extends PimaxBaseObject
{
    protected $oChildrens = null;
    
    protected $oParent = null;
    
    protected $nItemsCount = null;
    
    protected $oItems = null;

    protected $aFields = array(
        "ID", "CODE", "EXTERNAL_ID", "XML_ID", "TMP_ID", "IBLOCK_ID", 
        "IBLOCK_TYPE_ID", "IBLOCK_CODE", "IBLOCK_EXTERNAL_ID",
        "IBLOCK_SECTION_ID", "TIMESTAMP_X", "SORT", "NAME", "ACTIVE", 
        "GLOBAL_ACTIVE", "PICTURE", "DESCRIPTION", "DESCRIPTION_TYPE", 
        "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SEARCHABLE_CONTENT", 
        "SECTION_PAGE_URL", "MODIFIED_BY", "DATE_CREATE", "CREATED_BY", 
        "DETAIL_PICTURE", "ELEMENT_CNT", "SOCNET_GROUP_ID", "LIST_PAGE_URL"
    );
    
    protected $sIBlock;
    
    protected $sID;

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
        }

        return false;
    }

    public function get($sName, $aParams = array())
    {
        if ($this->isExists($sName)) {
            
            if ($sName == 'Picture') {
                return CFile::GetPath($this->aData[$sName]);
            } else if ($sName == 'TimestampX' || $sName == 'DateCreate') {
                return new PimaxDateTime($this->aData[$sName]);
            } else {
                return $this->aData[$sName];
            }
            
        }

        return false;
    }
    
    public function childrens()
    {
        if ($this->oChildrens == null) {
            $this->oChildrens = IbSectionFinder::getInstance()->from($this->getIblockId())->where(array('SectionId' => $this->getId()))->order(array('name' => 'asc'))->fetchAll();
        }
        
        return $this->oChildrens;
    }
    
    public function get_parent()
    {
        if ($this->getDepthLevel() == 1) {
            return false;
        } else {
            if ($this->oParent == null) {
                $this->oParent = IbSectionFinder::getInstance()->from($this->getIblockId())->where(array('Id' => $this->getIblockSectionId()))->fetch();
            }
            
            return $this->oParent;
        }
    }
    
    public function items_count()
    {
        return $this->getElementCnt();
    }
    
    public function items()
    {
        if ($this->oItems == null) {
            $this->oItems = IbFinder::getInstance()->from($this->getIblockId())->where(array('SectionId' => $this->getId()))->fetchAll();
        }
        
        return $this->oItems;
    }
    
    public function save()
    {
        $nSectionId = 0;
        
        if ((int)$this->sIBlock && !empty($this->aData))
            if ($this->sID)
                $nSectionId = IbSectionUpdater::getInstance()->update($this->sIBlock, $this->sID)->import($this->aData)->save();
            else
                $nSectionId = IbSectionUpdater::getInstance()->create($this->sIBlock)->import($this->aData)->save();
        
        return $nSectionId;
    }
    
    public function delete()
    {
        if ($this->sID)
            IbSectionUpdater::getInstance()->delete('', $this->sID);
    }
}