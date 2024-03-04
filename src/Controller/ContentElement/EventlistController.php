<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\ModuleEventlist;
use Contao\ModuleModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ContentElement(category="includes")
 */
class EventlistController
{
    public function __invoke(Request $request, ContentModel $model, string $section): Response
    {
        $moduleModel = ModuleModel::findByPk($model->module);

        if (null === $moduleModel || 'eventlist' !== $moduleModel->type) {
            return new Response();
        }

        $moduleModel->preventSaving();
        $moduleModel->cal_categories = $model->cal_categories;

        if (!empty(StringUtil::deserialize($model->headline, true)['value'])) {
            $moduleModel->headline = $model->headline;
        }

        $module = new ModuleEventlist($moduleModel, $section);

        return new Response($module->generate());
    }
}
