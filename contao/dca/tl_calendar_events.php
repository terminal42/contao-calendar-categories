<?php

// Palettes
use Contao\CoreBundle\DataContainer\PaletteManipulator;

$paletteManipulator = PaletteManipulator::create()
    ->addLegend('category_legend', 'title_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('categories', 'category_legend', PaletteManipulator::POSITION_APPEND)
;

foreach ($GLOBALS['TL_DCA']['tl_calendar_events']['palettes'] as $name => $palette) {
    if (is_string($palette)) {
        $paletteManipulator->applyToPalette($name, 'tl_calendar_events');
    }
}

// Fields
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['categories'] = [
    'exclude' => true,
    'filter' => true,
    'inputType' => 'picker',
    'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'tl_class' => 'clr'],
    'relation' => [
        'type' => 'haste-ManyToMany',
        'load' => 'lazy',
        'table' => 'tl_calendar_category',
        'referenceColumn' => 'event_id',
        'fieldColumn' => 'category_id',
        'relationTable' => 'tl_calendar_events_categories',
    ],
];
