<?php

class BatalinePicture extends BatalineFile
{
    protected $aThumb;
    
    /// Возращает ширину в пикселях
    public function getThumbWidth() {
        return $this->aThumb['Width'];
    }
    
    /// Возвращает высоту в пикселях
    public function getThumbHeight() {
        return $this->aThumb['Height'];
    }
    
    /** 
     * Создает уменьшенную копию изображения и возвращает ссылку на нее
     * 
     * @param type $nWidth
     * @param type $nHeight
     * @param type $bCrop - обрезать ли изображение
     */
    public function getThumbUrl($nWidth, $nHeight, $bCrop = false) {
        if (!is_array($this->aThumb) || empty($this->aThumb)) {
            $aImage = array();
            if (!empty($this->aData)) {
                foreach ($this->aData as $key => $aItem) {
                    $aImage[$this->reverseNormalizeFieldName($key)] = $aItem;
                }
            }
            $arFileTmp = CFile::ResizeImageGet(
                $aImage,
                array("width" => $nWidth, 'height' => $nHeight),
                ($bCrop)?BX_RESIZE_IMAGE_EXACT:BX_RESIZE_IMAGE_PROPORTIONAL,
                false
            );
            $arSize = getimagesize($_SERVER["DOCUMENT_ROOT"].$arFileTmp["src"]);
            $this->aThumb = array(
                'Src' => $arFileTmp["src"],
                'Width' => IntVal($arSize[0]),
                'Height' => IntVal($arSize[1]),
            );
        }
        return $this->aThumb['Src'];
    }
    
    /// Алиас для метода getSrc()
    public function __toString()
    {
        return $this->getSrc();
    }
}