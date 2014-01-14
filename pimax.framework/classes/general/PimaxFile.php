<?php

class PimaxFile extends PimaxBaseObject
{
    protected $sID;
    
    protected $sPath;
    
    public function __construct($sFile)
    {
//        if (is_string($sFile))
//            $this->sPath = $sFile;
//        elseif (is_int($sFile)) {
            $this->sID = $sFile;
//            $this->sPath = CFile::GetPath($this->sID);
            $aData = array();
            $aFile = CFile::GetFileArray($this->sID);
            if (!empty($aFile)) {
                foreach ($aFile as $key => $aItem) {
                    $aData[$this->normalizeFieldName($key)] = $aItem;
                }
            }
            parent::__construct($aData);
            $this->sPath = $this->aData['Src'];
//        }
    }
    
    /// Возвращает путь к файлу (CFile::GetPath($fileId))
    public function getPath()
    {
        return $this->sPath;
    }
    
    /// Алиас для метода getPath()
    public function __toString()
    {
        return $this->getPath();
    }
    
    /// Проверяет на существование файла
    public function isExists()
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$this->sPath))
            return true;
        else
            return false;
    }
    
    /// Возвращает название файла без пути
    public function getName()
    {
        //ТУДУ: проверка, получено ли уже имя
        return $this->aData['OriginalName'];
    }
    
    /// Возвращает расширение файла
    public function getExtension()
    {
        return end(explode('.', $this->sPath));
    }
    
    /// Возвращает размер файла в байтах
    public function getSize()
    {
        return $this->aData['FileSize'];
    }
}