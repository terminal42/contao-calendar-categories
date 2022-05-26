<?php

// Add global operations
\Contao\ArrayUtil::arrayInsert(
    $GLOBALS['TL_DCA']['tl_calendar']['list']['global_operations'], 1, [
        'categories' => [
            'href' => 'table=tl_calendar_category',
            'icon' => 'bundles/terminal42calendarcategories/icon.png',
            'attributes' => 'onclick="Backend.getScrollOffset()"',
        ],
    ]
);
