<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\EventListener;

use Codefog\HasteBundle\Model\DcaRelationsModel;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Database;
use Contao\Events;
use Contao\StringUtil;
use Haste\Model\Model;

/**
 * @Hook("getAllEvents")
 */
class FilterEventListListener
{
    public function __invoke(array $events, array $calendars, int $start, int $end, Events $module): array
    {
        $categories = StringUtil::deserialize($module->cal_categories);

        if (!\is_array($categories) || 0 === \count($categories)) {
            return $events;
        }

        $categories = Database::getInstance()->getChildRecords($categories, 'tl_calendar_category', false, $categories);

        if (class_exists(DcaRelationsModel::class)) {
            $eventIds = DcaRelationsModel::getReferenceValues('tl_calendar_events', 'categories', $categories);
        } else {
            $eventIds = Model::getReferenceValues('tl_calendar_events', 'categories', $categories);
        }

        if (0 === \count($eventIds)) {
            return [];
        }

        $eventIds = array_map('intval', array_unique($eventIds));

        // Filter out the events out of the module scope settings
        foreach ($events as $k => $v) {
            foreach ($v as $kk => $vv) {
                foreach ($vv as $kkk => $event) {
                    if (!\in_array((int) $event['id'], $eventIds, true)) {
                        unset($events[$k][$kk][$kkk]);
                    } else {
                        $events[$k][$kk][$kkk]['categories'] = $categories;
                    }
                }

                if (0 === \count($events[$k][$kk])) {
                    unset($events[$k][$kk]);
                }
            }

            if (0 === \count($events[$k])) {
                unset($events[$k]);
            }
        }

        return $events;
    }
}
