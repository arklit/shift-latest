<?php

    namespace App\Orchid\Abstractions;

    use Illuminate\Database\Eloquent\Builder;
    use Orchid\Screen\Actions\Link;
    use Orchid\Screen\Screen;
    use function session;

    abstract class ListScreenPattern extends Screen
    {
        /**
         * Модель, используемая для получения данных
         * @var Builder
         */
        protected Builder $model;

        /**
         * Количество экземпляров модели, выводимое на страницу
         * @var int
         */
        protected int $paginate = 10;

        /**
         * Имя роута для списка (используется для редиректа на этот список, после редактирования элемента)
         * @var string|null
         */
        protected ?string $listRedirect = '';

        /**
         * Имя роута, используемого для редактирования элемента
         * @var string|null
         */
        protected ?string $updateRoute = '';

        /**
         * Название скоупа (если применяется) для фильтрации моделей
         * @var string|null
         */
        protected ?string $scope;

        /**
         * Параметры для редиректа к списку моделей (номер страницы)
         * @var array
         */
        protected array $redirectParams = [];

        public function query()
        {
            $items = $this->model->paginate($this->paginate);
            $this->redirectParams = ['page' => $items->currentPage()];
            $this->setRedirect();

            return [
                'items' => $items,
            ];
        }

        public function commandBar()
        {
            return [
                Link::make('Создать')->icon('plus')->route($this->route->create())->rawClick(),
            ];
        }

        protected function setRedirect(string $uri = null)
        {
            session()->put('listRedirect', $this->listRedirect);
            session()->put('redirectParams', $this->redirectParams);
        }
    }
