<?php

    namespace App\Models;

    use App\Orchid\RocontModule\Traits\IsActiveScopeTrait;
    use App\Orchid\RocontModule\Traits\SortedScopeTrait;

    class Seo extends ProtoModel
    {
        public const TABLE_NAME = 'seos';
        protected $table = self::TABLE_NAME;
        protected $allowedSorts = ['id', 'is_active', 'sort', 'title', 'created_at'];

        use IsActiveScopeTrait;
        use SortedScopeTrait;

    }

