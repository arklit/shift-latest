<?php

    namespace App\Repositories;

    use App\Models\Article;
    use App\Models\Product;
    use Illuminate\Support\Collection;

    class CommonRepository extends AbstractRepository
    {
        private static $instance;

        public static function take(): static
        {
            return static::$instance ?? (static::$instance = new static());
        }

        public function getProductsByCharacteristics(array $characteristics, int $currentID): Collection
        {
            $json = [];
            foreach ($characteristics as $characteristic) {
                $json[] = "JSON_CONTAINS(`characteristics`, '{\"code\":\"$characteristic\"}')";
            }

            $whereJson = implode(' OR ', $json);

            $sql = "
                SELECT *
                FROM products AS p
                WHERE p.is_active = 1 AND p.id != $currentID
                AND ($whereJson)
                ORDER BY sort ASC
            ";

            return collect(self::executeSelectAll($sql, Product::class));
        }

        public function getRelativeLastArticles(int $articleID, string $categoryCode): Collection
        {
            $sql = "
                SELECT ar.*, ac.code
                FROM articles AS ar
                LEFT JOIN article_categories AS ac ON category_id = ac.id
                WHERE ar.publication_date <= now()
                AND ar.id != $articleID
                ORDER BY field(ac.code, '$categoryCode') DESC, publication_date DESC
                LIMIT 4;
            ";

            return collect(self::executeSelectAll($sql, Article::class));
        }
    }
