<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Symfony\Contracts\Translation\TranslatorInterface;
use Terminal42\CalendarCategoriesBundle\Model\CalendarCategoryModel;

#[AsCallback('tl_calendar_events', 'list.label.label')]
class EventLabelListener
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function __invoke(array $row, string $label): string
    {
        $categories = CalendarCategoryModel::findByEvent((int) $row['id']);
        $label = (new \tl_calendar_events())->listEvents($row, $label);

        if ($categories) {
            $label .= \sprintf(
                '<span style="position:relative;top:-1px;margin-left:3px" title="%s: %s"><img src="bundles/terminal42calendarcategories/icon.svg" alt=""></span>',
                $this->translator->trans('MSC.calendarCategoriesPicker', [], 'contao_default'),
                implode(', ', $categories->fetchEach('name')),
            );
        }

        return $label;
    }
}
