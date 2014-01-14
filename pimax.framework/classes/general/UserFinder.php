<?php

/**
 * UserFinder:
 *
 * @package bataline_framework
 * @subpackage core
 * @version 1.0
 */
class UserFinder
{
    protected $mCriteria;
    protected $sOrderBy;
    protected $sOrderDir;

    private static $instance = false;

    private function __construct()
    {
        $this->clear();
    }

    private function __clone() {}

    public function getInstance()
    {
        if (self::$instance == false) {
            self::$instance = new UserFinder();
        }

        return self::$instance;
    }

    public function criteria($mCriteria)
    {
        $this->mCriteria = $mCriteria;

        return $this;
    }

    public function order($sOrderBy, $sOrderDir = "asc")
    {
        $this->sOrderBy = $sOrderBy;
        $this->sOrderDir = $sOrderDir;

        return $this;
    }

    public function where($mCriteria)
    {
        return $this->criteria($mCriteria);
    }

    public function fetch()
    {

        $aFilter = array();

        // ”слови€ выборки
        if ($this->mCriteria) {
            $aCr = array();
            if (is_object($this->mCriteria)) {
                $aCr = $this->mCriteria->toArray();
            } else if (is_array($this->mCriteria)) {
                foreach ($this->mCriteria as $by => $dir) {
                    $aCr[$this->reverseNormalizeFieldName($by)] = $dir;
                }
            }
            $aFilter = array_merge($aFilter, $aCr);
        }

        $oResult = false;
        $res = CUser::GetList(($by=$this->sOrderBy), ($order=$this->sOrderDir), $aFilter);
        while($ob = $res->GetNext()){
            $oResult = new BatalineUser($ob);
            break;
        }
        $this->clear();

        return $oResult;
    }

    public function fetchAll()
    {
        $aFilter = array();

        // Условие выборки
        if ($this->mCriteria) {
            $aCr = array();
            if (is_object($this->mCriteria)) {
                $aCr = $this->mCriteria->toArray();
            } else if (is_array($this->mCriteria)) {
                foreach ($this->mCriteria as $by => $dir) {
                    $aCr[$this->reverseNormalizeFieldName($by)] = $dir;
                }
            }
            $aFilter = array_merge($aFilter, $aCr);
        }

        $oResult = new BatalineCollection();
        $res = CUser::GetList(($by=$this->sOrderBy), ($order=$this->sOrderDir), $aFilter);
        while($ob = $res->GetNext()){
            $oObject = new BatalineUser($ob);
            $oResult->add($oObject);
        }
        $this->clear();

        return $oResult;
    }

    public function clear()
    {
        $this->sOrderBy = 'timestamp_x';
        $this->sOrderDir = "asc";
        $this->mCriteria = false;

        return true;
    }

    protected function normalizeFieldName($sField)
    {
        return Bataline::normalizeFieldName($sField);
    }

    protected function reverseNormalizeFieldName($sField)
    {
        return Bataline::reverseNormalizeFieldName($sField);
    }
}
