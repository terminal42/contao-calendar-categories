<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Terminal42\CalendarCategoriesBundle\Controller\FrontendModule\CalendarCategoriesListController;

PaletteManipulator::create()
    ->addField('cal_categories', 'config_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('cal_categoriesFilter', 'config_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('eventlist', 'tl_module')
;

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_categories'] = [
    'exclude' => true,
    'inputType' => 'picker',
    'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'tl_class' => 'clr'],
    'sql' => ['type' => 'blob', 'notnull' => false],
    'relation' => ['type' => 'lazy', 'table' => 'tl_calendar_category'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_categoriesRoot'] = [
    'exclude' => true,
    'inputType' => 'picker',
    'eval' => ['fieldType' => 'radio', 'tl_class' => 'clr'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
    'relation' => ['type' => 'lazy', 'table' => 'tl_calendar_category'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_categoriesReset'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 m12'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['cal_categoriesFilter'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_module']['palettes'][CalendarCategoriesListController::TYPE] = '{title_legend},name,headline,type;{config_legend},cal_categoriesRoot,showLevel,cal_categoriesReset;{redirect_legend},jumpTo;{template_legend:hide},customTpl,navigationTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
