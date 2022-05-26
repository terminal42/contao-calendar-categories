<?php

// Palettes
\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addLegend('redirect_legend', 'config_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->addField('calendar_categories', 'config_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('eventlist', 'tl_module');

// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['calendar_categories'] = [
    'exclude' => true,
    'inputType' => 'calendarCategoriesPicker',
    'foreignKey' => 'tl_calendar_category.name',
    'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'tl_class' => 'clr'],
    'sql' => ['type' => 'blob', 'notnull' => false],
];
