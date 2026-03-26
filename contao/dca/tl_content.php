<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['eventlist'] = '{type_legend},type,headline;{include_legend},module,cal_categories;{protected_legend:collapsed},protected;{expert_legend:collapsed},guests,cssID;{invisible_legend:collapsed},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['cal_categories'] = [
    'exclude' => true,
    'inputType' => 'picker',
    'eval' => ['multiple' => true, 'fieldType' => 'checkbox', 'tl_class' => 'clr'],
    'sql' => ['type' => 'blob', 'notnull' => false],
    'relation' => ['type' => 'lazy', 'table' => 'tl_calendar_category'],
];
