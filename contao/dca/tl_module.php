<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addField('cal_categories', 'cal_calendar')
    ->applyToPalette('eventlist', 'tl_module')
;

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_categories'] = [
    'exclude' => true,
    'inputType' => 'picker',
    'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'tl_class' => 'clr'],
    'sql' => ['type' => 'blob', 'notnull' => false],
    'relation' => ['type' => 'lazy', 'table' => 'tl_calendar_category'],
];
