<?php

declare(strict_types=1);

namespace Terminal42\CalendarCategoriesBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\FrontendTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Template;
use Nyholm\Psr7\Uri;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Terminal42\CalendarCategoriesBundle\Model\CalendarCategoryModel;

/**
 * @FrontendModule(category="events", type=self::TYPE, template="mod_calendar_categories_list")
 */
class CalendarCategoriesListController extends AbstractFrontendModuleController
{
    public const TYPE = 'calendar_categories_list';
    public const PARAM_NAME = 'category';

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): Response
    {
        $template->categories = $this->renderCategories((int) $model->cal_categoriesRoot, $model, $request);

        return $template->getResponse();
    }

    private function renderCategories(int $pid, ModuleModel $model, Request $request, int $level = 1): string
    {
        $categories = CalendarCategoryModel::findBy(['pid = ?'], [$pid], ['order' => 'sorting ASC']);

        if (null === $categories) {
            return '';
        }

        $template = new FrontendTemplate($model->navigationTpl ?: 'nav_default');
        $template->pid = $pid;
        $template->type = static::class;
        $template->level = 'level_'.$level;

        $items = [];
        $query = [];

        if ($jumpTo = PageModel::findById($model->jumpTo)) {
            $uri = new Uri($jumpTo->getAbsoluteUrl());
        } else {
            $uri = new Uri($request->getUri());
            parse_str($uri->getQuery(), $query);
        }

        if (1 === $level && $model->cal_categoriesReset) {
            $resetLabel = $this->translator->trans('categories_list_reset.label', [], 'calendar_categories');
            $resetTitle = $this->translator->trans('categories_list_reset.title', [], 'calendar_categories');
            unset($query[self::PARAM_NAME]);

            $items[] = [
                'pageTitle' => null,
                'title' => StringUtil::specialcharsAttribute($resetTitle),
                'link' => StringUtil::specialchars($resetLabel),
                'isActive' => null === $request->query->get(self::PARAM_NAME),
                'href' => StringUtil::specialcharsUrl($uri->withQuery(http_build_query($query))),
                'class' => 'reset',
                'accesskey' => '',
                'tabindex' => '',
                'target' => '',
            ];
        }

        ++$level;

        foreach ($categories as $category) {
            $item = $category->row();
            $item['title'] = StringUtil::specialcharsAttribute($category->name);
            $item['pageTitle'] = null;
            $item['link'] = StringUtil::specialchars($category->name);
            $item['isActive'] = (int) $category->id === (int) $request->query->get(self::PARAM_NAME);
            $item['class'] = 'category';
            $item['accesskey'] = '';
            $item['tabindex'] = '';
            $item['target'] = '';

            if (!$model->showLevel || $model->showLevel >= $level) {
                $item['subitems'] = $this->renderCategories((int) $category->id, $model, $request, $level);
            }

            $query[self::PARAM_NAME] = (int) $category->id;
            $item['href'] = StringUtil::specialcharsUrl($uri->withQuery(http_build_query($query)));

            $items[] = $item;
        }

        $template->items = $items;

        return $template->parse();
    }
}
