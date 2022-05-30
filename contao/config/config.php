<?php

// Backend modules
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_category';

// Models
$GLOBALS['TL_MODELS']['tl_calendar_category'] = \Terminal42\CalendarCategoriesBundle\Model\CalendarCategoryModel::class;
