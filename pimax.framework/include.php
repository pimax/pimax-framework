<?php

IncludeModuleLangFile(__FILE__);

global $MESS, $DOCUMENT_ROOT;

CModule::AddAutoloadClasses(
    'pimax.framework',
    array(
        'Pimax' => 'classes/general/Pimax.php',
        'PimaxCollection' => 'classes/general/PimaxCollection.php',
        'PimaxPropertiesCollection' => 'classes/general/PimaxPropertiesCollection.php',
        'PimaxBaseObject' => 'classes/general/PimaxBaseObject.php',
        'PimaxDateTime' => 'classes/general/PimaxDateTime.php',
        'PimaxObject' => 'classes/general/PimaxObject.php',
        'PimaxProperty' => 'classes/general/PimaxProperty.php',
        'PimaxSection' => 'classes/general/PimaxSection.php',
        'PimaxUser' => 'classes/general/PimaxUser.php',
        'IbFinder' => 'classes/general/IbFinder.php',
        'IbSectionFinder' => 'classes/general/IbSectionFinder.php',
        'UserFinder' => 'classes/general/UserFinder.php',
        'IbUpdater' => 'classes/general/IbUpdater.php',
    )
);
?>