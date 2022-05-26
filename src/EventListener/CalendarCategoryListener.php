<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\EventListener;

use Contao\Backend;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;

class CalendarCategoryListener
{
    /**
     * @Callback(table="tl_calendar_category", target="list.paste_button_callback")
     */
    public function onPasteButtonCallback(DataContainer $dc, array $row, string $table, bool $cr, array $clipboard = null): string
    {
        $disablePA = false;
        $disablePI = false;

        // Disable all buttons if there is a circular reference
        if (null !== $clipboard && (('cut' === $clipboard['mode'] && ($cr || (int) $clipboard['id'] === (int) $row['id'])) || ('cutAll' === $clipboard['mode'] && ($cr || \in_array((int) $row['id'], array_map('intval', $clipboard['id']), true))))) {
            $disablePA = true;
            $disablePI = true;
        }

        $return = '';

        if ($row['id'] > 0) {
            $return = $this->generatePasteImage('pasteafter', $disablePA, $table, $row, $clipboard);
        }

        return $return.$this->generatePasteImage('pasteinto', $disablePI, $table, $row, $clipboard);
    }

    /**
     * Generate the paste image.
     */
    private function generatePasteImage(string $type, bool $disabled, string $table, array $row, array $clipboard = null): string
    {
        if ($disabled) {
            return Image::getHtml($type.'_.svg').' ';
        }

        $url = sprintf('act=%s&amp;mode=%s&amp;pid=%s', $clipboard['mode'], ('pasteafter' === $type ? 1 : 2), $row['id']);

        // Add the ID to the URL if the clipboard does not contain any
        if (!\is_array($clipboard['id'])) {
            $url .= '&amp;id='.$clipboard['id'];
        }

        return sprintf(
            '<a href="%s" title="%s" onclick="Backend.getScrollOffset()">%s</a> ',
            Backend::addToUrl($url),
            StringUtil::specialchars(sprintf($GLOBALS['TL_LANG'][$table][$type][1], $row['id'])),
            Image::getHtml($type.'.svg', sprintf($GLOBALS['TL_LANG'][$table][$type][1], $row['id']))
        );
    }
}
