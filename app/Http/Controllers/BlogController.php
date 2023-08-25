<?php

namespace App\Http\Controllers;

use App\Enums\ClientRoutes;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\ArticlesService;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    /**
     * Страница со списком категорий и статей
     * @route /articles
     * @method GET
     * @param ArticlesService $articlesService
     * @param int|null $page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getArticlesList(ArticlesService $articlesService, ?int $page = null)
    {
        $page = $page ?: 1;

        $categories = $articlesService->getCategoriesWithCurrent();
        $articles = $articlesService->getArticlesList($page);
        $paginator = $articles->linkCollection()->paginizate();

        $articlesService->setBreadCrumbs(
            ClientRoutes::BLOG_LIST_PAGE, ClientRoutes::BLOG_LIST, "Страница " . $page, [$page]);

        return view('modules.blog.list', compact('articles', 'categories', 'paginator'));
    }

    /**
     * Страница со статьями из выбранной категории
     * @route /articles/{categoryCode}
     * @method GET
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getArticlesCategory(ArticlesService $articlesService, string $categoryCode, ?int $page = null)
    {
        $page = $page ?: 1;

        $currentCategory = ArticleCategory::query()->active()->code($categoryCode)->firstOrFail();

        $categories = $articlesService->getCategoriesWithCurrent($currentCategory);

        $articles = $articlesService->getArticlesList($page, $categoryCode);

        abort_if($articles->isEmpty(), 404);
        $paginator = $articles->linkCollection()->paginizate();

        $articlesService->setBreadCrumbs(
            ClientRoutes::BLOG_CATEGORY, ClientRoutes::BLOG_LIST, $currentCategory->getTitle(), [$categoryCode]);

        if ($page > 1) {
            $articlesService->setBreadCrumbs(
                ClientRoutes::BLOG_CATEGORY_PAGE, ClientRoutes::BLOG_CATEGORY, "Страница $page", [$categoryCode, $page]);
        }

        return view('modules.blog.list', compact('articles', 'categories', 'currentCategory', 'paginator'));
    }

    /**
     * Подробная страница конкретной статьи
     * @route /articles/{categoryCode}/{articleCode}
     * @method GET
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getArticlePage(ArticlesService $articlesService, string $categoryCode, string $articleSlug)
    {
        $article = Article::query()->with('category')->active()->publicated()->where('slug', '=', $articleSlug)
            ->whereHas('category', fn($q) => $q->active()->code($categoryCode))->firstOrFail();

        $otherArticles = Article::query()->whereHas('category', fn($q) => $q->active()->code($categoryCode))
            ->active()->publicated()->publicationSorted()->where('slug', '!=', $articleSlug)
            ->with('category')->limit(4)->get();

        $articlesService->setBreadCrumbs(
            ClientRoutes::BLOG_CATEGORY, ClientRoutes::BLOG_LIST, $article->category->getTitle(), [$categoryCode]);
        $articlesService->setBreadCrumbs(
            ClientRoutes::BLOG_ARTICLE, ClientRoutes::BLOG_CATEGORY, $article->getTitle(), [$categoryCode, $articleSlug]);

        return view('modules.blog.item', compact('article', 'otherArticles'));
    }
}
