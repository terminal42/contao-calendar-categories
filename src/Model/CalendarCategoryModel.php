<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\Model;

use Codefog\HasteBundle\Model\DcaRelationsModel;
use Contao\Model;
use Contao\Model\Collection;

class CalendarCategoryModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_calendar_category';

    /**
     * @return Collection<CalendarCategoryModel>|array<CalendarCategoryModel>|null
     */
    public static function findByEvent(array|int $eventId, array $arrOptions = []): Collection|array|null
    {
        if (0 === \count($ids = DcaRelationsModel::getRelatedValues('tl_calendar_events', 'categories', $eventId))) {
            return null;
        }

        $t = static::getTable();
        $columns = ["$t.id IN (".implode(',', array_map(intval(...), array_unique($ids))).')'];
        $values = [];

        return static::findBy($columns, $values, array_merge(['order' => "$t.sorting"], $arrOptions));
    }
}
