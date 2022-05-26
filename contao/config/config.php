<?php

// Backend modules
$GLOBALS['BE_MOD']['content']['calendar']['tables'][] = 'tl_calendar_category';

// Backend form fields
$GLOBALS['BE_FFL']['calendarCategoriesPicker'] = \Terminal42\CalendarCategoriesBundle\Widget\CalendarCategoriesPickerWidget::class;

// Models
$GLOBALS['TL_MODELS']['tl_calendar_category'] = \Terminal42\CalendarCategoriesBundle\Model\CalendarCategoryModel::class;
