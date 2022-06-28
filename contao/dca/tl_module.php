<?php

// Palettes
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('redirect_legend', 'config_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addField('cal_categories', 'config_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('eventlist', 'tl_module');

// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['cal_categories'] = [
    'exclude' => true,
    'inputType' => 'picker',
    'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'tl_class' => 'clr'],
    'sql' => ['type' => 'blob', 'notnull' => false],
    'relation' => ['type' => 'lazy', 'table' => 'tl_calendar_category'],
];
