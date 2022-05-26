<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\ContaoManager;

use Contao\CalendarBundle\ContaoCalendarBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Terminal42\CalendarCategoriesBundle\Terminal42CalendarCategoriesBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            (new BundleConfig(Terminal42CalendarCategoriesBundle::class))
                ->setLoadAfter([ContaoCoreBundle::class, ContaoCalendarBundle::class]),
        ];
    }
}
