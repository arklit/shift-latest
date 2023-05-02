<?php

namespace App\Orchid\Abstractions;

use App\Enums\OrchidRoutes;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use function session;

abstract class ListScreenPattern extends Screen
{
    /** Модель, используемая для получения данных
     * @var Builder
     */
    protected Builder $model;

    /** Количество экземпляров модели, выводимое на страницу
     * @var int
     */
    protected int $paginate = 10;

    /** Параметры для редиректа к списку моделей (номер страницы)
     * @var array
     */
    protected array $redirectParams = [];

    /** Enum в котором хранятся данные по именам роутов для админки
     * @var OrchidRoutes
     */
    protected OrchidRoutes $route;

    public function query(): array
    {
        $items = $this->model->paginate($this->paginate);
        $this->redirectParams = ['page' => $items->currentPage()];
        $this->setRedirect();

        return [
            'items' => $items,
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Создать')->icon('plus')->route($this->route->create()),
        ];
    }

    protected function setRedirect(string $uri = null): void
    {
        session()->put('listRedirect', $this->route->list());
        session()->put('redirectParams', $this->redirectParams);
    }
}
