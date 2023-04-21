<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Helpers\Constants;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use App\Repositories\CommonRepository;

class BlogController extends Controller
{
    private int $perPage = Constants::DEFAULT_ITEMS_PER_PAGE;

    /**
     * Страница со списком категорий и статей
     * @route /articles
     * @method GET
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getArticlesList(Request $request)
    {
        $page = $this->getPage($request);

        $categories = ArticleCategory::query()->active()->sorted('asc')->whereHas('articles', fn($q) => $q->active()->publicated())->get();
        $articles = Article::query()->select('*')->active()->publicated()->publicationSorted()->paginate($this->perPage, $page);

        return view('pages.articles', compact('categories', 'articles'));
    }

    /**
     * Страница со статьями из выбранной категории
     * @route /articles/{categoryCode}
     * @method GET
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getArticlesCategory(Request $request, string $categoryCode)
    {
        $page = $this->getPage($request);

        $articles = Article::query()->select('*')->active()->publicated()->publicationSorted()
            ->whereHas('category', fn($q) => $q->active()->code($categoryCode))->paginate($this->perPage, $page);
        abort_if($articles->isEwmpty(), 404);

        return $this->responseData(compact('articles'));
    }

    /**
     * Подробная страница конкретной статьи
     * @route /articles/{categoryCode}/{articleCode}
     * @method GET
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getArticlePage(string $categoryCode, string $articleSlug)
    {
        $article = Article::query()->active()->publicated()->where('slug', '=', $articleSlug)
            ->whereHas('category', fn($q) => $q->active()->code($categoryCode))->firstOrFail();
        $lastArticles = CommonRepository::take()->getRelativeLastArticles($article->id, $categoryCode);


        return view('pages.article', compact('article', 'lastArticles'));
    }

    protected function getPage(Request $request)
    {
        $page = $request->query('page');
        return !is_numeric($page) ? 1 : $page;
    }
}
