<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsCallback('tl_calendar_category', 'config.onload')]
class BacklinkListener
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function __invoke(): void
    {
        if ($this->requestStack->getCurrentRequest()?->query->has('picker')) {
            return;
        }

        $GLOBALS['TL_DCA']['tl_calendar_category']['config']['backlink'] = 'do=calendar';
    }
}
