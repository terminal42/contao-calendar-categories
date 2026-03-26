<?php

use Terminal42\CalendarCategoriesBundle\Model\CalendarCategoryModel;

$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_category';

$GLOBALS['TL_MODELS']['tl_calendar_category'] = CalendarCategoryModel::class;
