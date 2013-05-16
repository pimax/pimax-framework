<?php

IncludeModuleLangFile(__FILE__);

global $MESS, $DOCUMENT_ROOT;

CModule::AddAutoloadClasses(
    'bataline.framework',
    array(
        'Bataline' => 'classes/general/Bataline.php',
        'BatalineCollection' => 'classes/general/BatalineCollection.php',
        'BatalinePropertiesCollection' => 'classes/general/BatalinePropertiesCollection.php',
        'BatalineBaseObject' => 'classes/general/BatalineBaseObject.php',
        'BatalineDateTime' => 'classes/general/BatalineDateTime.php',
        'BatalineObject' => 'classes/general/BatalineObject.php',
        'BatalineProperty' => 'classes/general/BatalineProperty.php',
        'BatalineSection' => 'classes/general/BatalineSection.php',
        'BatalineUser' => 'classes/general/BatalineUser.php',
        'IbFinder' => 'classes/general/IbFinder.php',
        'IbSectionFinder' => 'classes/general/IbSectionFinder.php',
        'UserFinder' => 'classes/general/UserFinder.php',
        'IbUpdater' => 'classes/general/IbUpdater.php',
    )
);
?>