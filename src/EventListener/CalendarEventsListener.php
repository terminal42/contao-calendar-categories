<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\Input;
use Doctrine\DBAL\Connection;

class CalendarEventsListener
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Callback(table="tl_calendar_events", target="fields.categories.options_callback")
     */
    public function onCategoriesOptionsCallback(): array
    {
        // Do not generate the options for other views than listings
        if (Input::get('act') && 'select' !== Input::get('act')) {
            return [];
        }

        return $this->generateOptionsRecursively();
    }

    /**
     * Generate the options recursively.
     */
    private function generateOptionsRecursively(int $pid = 0, string $prefix = ''): array
    {
        $options = [];
        $records = $this->connection->fetchAllAssociative('SELECT * FROM tl_calendar_category WHERE pid=? ORDER BY sorting', [$pid]);

        foreach ($records as $record) {
            $options[$record['id']] = $prefix.$record['name'];

            foreach ($this->generateOptionsRecursively((int) $record['id'], $record['name'].' / ') as $k => $v) {
                $options[$k] = $v;
            }
        }

        return $options;
    }
}
