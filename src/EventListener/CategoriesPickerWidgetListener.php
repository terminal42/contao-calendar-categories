<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\EventListener;

use Contao\CoreBundle\Exception\ResponseException;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Terminal42\CalendarCategoriesBundle\Widget\CalendarCategoriesPickerWidget;

class CategoriesPickerWidgetListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Hook("executePostActions")
     */
    public function onExecutePostActions(string $action, DataContainer $dc): void
    {
        if ('reloadCalendarCategoriesWidget' === $action) {
            $this->reloadCalendarCategoriesWidget($dc);
        }
    }

    /**
     * Reload the calendar categories widget.
     */
    private function reloadCalendarCategoriesWidget(DataContainer $dc): void
    {
        $id = Input::get('id');
        $field = $dc->inputName = Input::post('name');

        // Handle the keys in "edit multiple" mode
        if ('editAll' === Input::get('act')) {
            $id = preg_replace('/.*_([0-9a-zA-Z]+)$/', '$1', $field);
            $field = preg_replace('/(.*)_[0-9a-zA-Z]+$/', '$1', $field);
        }

        $dc->field = $field;

        // The field does not exist
        if (!isset($GLOBALS['TL_DCA'][$dc->table]['fields'][$field])) {
            $this->logger->log(
                LogLevel::ERROR,
                sprintf('Field "%s" does not exist in DCA "%s"', $field, $dc->table),
                ['contao' => new ContaoContext(__METHOD__, TL_ERROR)]
            );

            throw new BadRequestHttpException('Bad request');
        }

        $value = null;

        // Load the value
        if ('overrideAll' !== Input::get('act') && $id > 0 && Database::getInstance()->tableExists($dc->table)) {
            $row = Database::getInstance()
                ->prepare('SELECT * FROM '.$dc->table.' WHERE id=?')
                ->limit(1)
                ->execute($id)
            ;

            // The record does not exist
            if ($row->numRows < 1) {
                $this->logger->log(
                    LogLevel::ERROR,
                    sprintf('A record with the ID "%s" does not exist in table "%s"', $id, $dc->table),
                    ['contao' => new ContaoContext(__METHOD__, TL_ERROR)]
                );

                throw new BadRequestHttpException('Bad request');
            }

            $value = $row->$field;
            $dc->activeRecord = $row;
        }

        // Call the load_callback
        if (\is_array($GLOBALS['TL_DCA'][$dc->table]['fields'][$field]['load_callback'] ?? null)) {
            foreach ($GLOBALS['TL_DCA'][$dc->table]['fields'][$field]['load_callback'] as $callback) {
                if (\is_array($callback)) {
                    $value = System::importStatic($callback[0])->{$callback[1]}($value, $dc);
                } elseif (\is_callable($callback)) {
                    $value = $callback($value, $dc);
                }
            }
        }

        // Set the new value
        $value = Input::post('value', true);

        // Convert the selected values
        if ($value) {
            $value = StringUtil::trimsplit("\t", $value);
            $value = serialize($value);
        }

        /** @var CalendarCategoriesPickerWidget $strClass */
        $strClass = $GLOBALS['BE_FFL']['calendarCategoriesPicker'];

        /** @var CalendarCategoriesPickerWidget $objWidget */
        $objWidget = new $strClass($strClass::getAttributesFromDca($GLOBALS['TL_DCA'][$dc->table]['fields'][$field], $dc->inputName, $value, $field, $dc->table, $dc));

        throw new ResponseException(new Response($objWidget->generate()));
    }
}
