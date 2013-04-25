<?php

class BatalineUser extends BatalineBaseObject
{
    protected $aFields = array(
        "ID", "XML_ID", "TIMESTAMP_X", "LOGIN", "PASSWORD", "CONFIRM_PASSWORD", 
        "STORED_HASH", "CHECKWORD", "ACTIVE", "NAME", "LAST_NAME", 
        "SECOND_NAME", "EMAIL", "LAST_LOGIN", "LAST_ACTIVITY_DATE", 
        "DATE_REGISTER", "LID", "ADMIN_NOTES", "EXTERNAL_AUTH_ID", 
        "PERSONAL_PROFESSION", "PERSONAL_WWW", "PERSONAL_ICQ", 
        "PERSONAL_GENDER", "PERSONAL_BIRTHDAY", "PERSONAL_PHOTO", 
        "PERSONAL_PHONE", "PERSONAL_FAX", "PERSONAL_MOBILE", 
        "PERSONAL_PAGER", "PERSONAL_STREET", "PERSONAL_MAILBOX", 
        "PERSONAL_CITY", "PERSONAL_STATE", "PERSONAL_ZIP", 
        "PERSONAL_COUNTRY", "PERSONAL_NOTES", "WORK_COMPANY", 
        "WORK_DEPARTMENT", "WORK_POSITION", "WORK_WWW", "WORK_PHONE", 
        "WORK_FAX", "WORK_PAGER", "WORK_STREET", "WORK_MAILBOX", 
        "WORK_CITY", "WORK_STATE", "WORK_ZIP", "WORK_COUNTRY", 
        "WORK_PROFILE", "WORK_LOGO", "WORK_NOTES"
    );
    
    protected $sID;
    
    public function __construct($mData=array(), $sID = 0)
    {
        /// Импорт основных полей
        $aData = array();
        if (is_array($mData)) {
            $aData = $mData;
            /// Добавление пользовательских полей
            foreach ($aData as $key => $value)
                if (strpos($key, 'Uf') === 0)
                    $this->aFields[] = $this->reverseNormalizeFieldName($key);
        }
        else {
            $userID = $mData;
            $aFilter = Array("ID" => $mData);
            $rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), $aFilter);
            $aUser = $rsUsers->Fetch();
            /// Подготовка массива к импорту
            $aData = array();
            if (!empty($aUser)) {
                foreach ($aUser as $key => $aItem) {
                    /// Добавление пользовательских полей
                    if (strpos($key, 'Uf') === 0)
                        $this->aFields[] = $this->reverseNormalizeFieldName($key);
                    $aData[$this->normalizeFieldName($key)] = $aItem;
                }
            }
        }
        parent::__construct($aData);

        $this->sID = $sID;
    }
    public function import($aData)
    {
        foreach ($aData as $key => $value)
            $this->addUfField($sName);
        
        parent::import($aData);
    }
    
    public function set($sName, $mValue)
    {
        $this->addUfField($sName);

        return parent::set($sName, $mValue);
    }
    
    public function get($sName, $aParams = array())
    {
        if ($this->isExists($sName)) {
            
            if ($sName == 'PersonalPhoto') {
                return CFile::GetPath($this->aData[$sName]);
            } else {
                return $this->aData[$sName];
            }
            
        }

        return false;
    }
    
    public function getFullName()
    {
        return $this->getName().' '.$this->getLastName();
    }
    
    
    public function save()
    {
        $nSectionId = 0;
        
        if (!empty($this->aData))
            if ($this->sID)
                $nSectionId = UserUpdater::getInstance()->update($this->sID)->import($this->aData)->save();
            else
                $nSectionId = UserUpdater::getInstance()->create()->import($this->aData)->save();
        
        return $nSectionId;
    }
    
    public function delete()
    {
        if ($this->sID)
            UserUpdater::getInstance()->delete($this->sID);
    }
    
    public function addUfField($sName)
    {
        if (strpos($sName, 'Uf') === 0 && !in_array($sName, $this->aFields))
            $this->aFields[] = $sName;
    }
}