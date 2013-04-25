<?php

CModule::IncludeModule('bataline_framework');

$oIbFinder = new IbFinder();
$oCollection = $oIbFinder->select('*')->from('news')->order('POS asc')->limit(10)->fetchAll();

if ($oCollection->count()) {
    foreach ($oCollection as $itm) {
        echo $itm->getName(), '<br />';
        echo 'Свойства: ', print_r($itm->properties->toString());
    }
}


// Создание объекта
$oIbUpdater = new IbUpdater();
$oElement = $oIbUpdater->create('news')->import(array())->save();

// Обновление объекта
$oIbUpdater = new IbUpdater();
$oElement = $oIbUpdater->update('news', 23)->import(array())->save();

// Удаление объекта
$oIbUpdater = new IbUpdater();
$oIbUpdater->delete('news', 32);