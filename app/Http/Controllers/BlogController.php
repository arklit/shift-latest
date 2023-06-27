<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

class BlogController extends Controller
{
    protected int $perPage = Constants::DEFAULT_ITEMS_PER_PAGE;
    protected string $list = 'web.articles.list';
    protected string $category = 'web.articles.category';
    protected string $page = 'web.articles.page';

    /**
     * Страница со списком категорий и статей
     * @route /articles
     * @method GET
     * @param Request $request
     * @return JsonResponse
     */
    public function getArticlesList(Request $request)
    {
        $page = $this->getPage($request);
        $categories = ArticleCategory::query()->active()->sorted('asc')->whereHas('articles', fn($q) => $q->active()->publicated())->get();
        $articles = Article::query()->whereHas('category', fn($q) => $q->active())->select('*')->active()->publicated()->publicationSorted()->paginate($this->perPage, $page);

        return $this->responseData(compact('categories', 'articles'));
    }

    /**
     * Страница со статьями из выбранной категории
     * @route /articles/{categoryCode}
     * @method GET
     * @return JsonResponse
     */
    public function getArticlesCategory(Request $request, string $categoryCode)
    {
        $page = $this->getPage($request);
        $currentCategory = ArticleCategory::query()->active()->code($categoryCode)->firstOrFail();
        $categories = ArticleCategory::query()->active()->sorted('asc')->whereHas('articles', fn($q) => $q->active()->publicated())->get();
        $articles = Article::query()->select('*')->active()->publicated()->publicationSorted()
            ->whereHas('category', fn($q) => $q->active()->code($categoryCode))->paginate($this->perPage, $page);
        abort_if($articles->isEmpty(), 404);

        Breadcrumbs::for($this->category, fn(Trail $t) => $t->parent($this->list)->push($currentCategory->getTitle(), route($this->category, [$categoryCode])));
        dd(Breadcrumbs::current());

        return $this->responseData(compact('articles'));
    }

    /**
     * Подробная страница конкретной статьи
     * @route /articles/{categoryCode}/{articleCode}
     * @method GET
     * @return JsonResponse
     */
    public function getArticlePage(string $categoryCode, string $articleSlug)
    {
        $article = Article::query()->active()->publicated()->where('slug', '=', $articleSlug)
            ->whereHas('category', fn($q) => $q->active()->code($categoryCode))->firstOrFail();

        Breadcrumbs::for($this->category, fn(Trail $t) => $t->parent($this->list)->push($category->getTitle(), route($this->category, [$categoryCode])));
        Breadcrumbs::for($this->page, fn(Trail $t) => $t->parent($this->category)->push($article->getTitle(), route($this->page, [$categoryCode, $articleSlug])));

        return $this->responseData(compact('article'));
    }

    protected function getPage(Request $request)
    {
        $page = $request->query('page');
        return !is_numeric($page) ? 1 : $page;
    }
}
