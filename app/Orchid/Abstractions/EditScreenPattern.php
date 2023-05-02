<?php

namespace App\Orchid\Abstractions;

use App\Enums\OrchidRoutes;
use App\Interfaces\ProtoInterface;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;
use function redirect;
use function session;

abstract class EditScreenPattern extends Screen
{
    /** Название страницы редактирования. Задаётся вручную или же автоматически подставляется как Updated/Created
     * @var string
     */
    public string $name = '';

    /** Переменная определяющая редактируется ли уже существующая запись или создаётся новая
     * @var bool
     */
    protected bool $exists = false;

    /** Имя роута, на который будет происходить редирект после сохранения записи
     * @var string|null
     */
    protected ?string $listRedirect = null;

    /** Параметры для редиректа к списку моделей (номер страницы)
     * @var array
     */
    protected array $redirectParams = [];

    /** Если true, то произойдёт редирект на основе данных из сессии, если false, то маршрут для перенаправления будет
     * взят из дефолтного значения переменной
     * @var bool
     */
    protected bool $redirectAfterUpdate = true;

    /** Определяет дефолтное значение сообщения об успешном создании записи
     * @var string
     */
    protected string $createMessage = 'Запись успешно создана';

    /** Определяет дефолтное значение сообщения об успешном редактировании записи
     * @var string
     */
    protected string $updateMessage = 'Запись успешно обновлена';

    /** Определяет дефолтное значение сообщения об успешном удалении записи
     * @var string
     */
    protected string $deleteMessage = 'Запись успешно удалена';

    /** Определяет дефолтное значение заголовка для редактирования записи
     * @var string
     */
    protected string $updateTitle = 'Редактирование записи';

    /** Определяет дефолтное значение заголовка для удаления записи
     * @var string
     */
    protected string $createTitle = 'Создание записи';

    /** Имя свойства (колонки в БД) у редактируемой сущности, в котором хранится её название (title, name, etc.)
     * @var string
     */
    protected string $titleColumnName = 'title';

    /** Enum в котором хранятся данные по именам роутов для админки
     * @var OrchidRoutes
     */
    protected OrchidRoutes $route;
    protected array $relations = [];

    protected function queryMake(ProtoInterface $item)
    {
        $this->redirectTo();
        $this->exists = $item->exists;
        $currentRoute = $this->exists ? $this->route->edit() : $this->route->create();
        $name = $this->exists ? $this->updateTitle : $this->createTitle;

        Breadcrumbs::for($currentRoute, fn(Trail $t) => $t->parent($this->route->list())->push($name, route($currentRoute, $item->id)));

        if (empty($this->name)) {
            $this->name = $item->exists ? $this->updateTitle : $this->createTitle;
        }

        return [
            'item' => $item,
        ];
    }

    protected function saveItem(ProtoInterface $item, $data)
    {
        $this->redirectTo();

        $itemTitle = empty($this->titleColumnName) ? ("#" . $item->id) : ('ID: ' . $item->id . ': ' . $item->{$this->titleColumnName});
        $this->updateMessage = $this->updateMessage ?: "Запись [$itemTitle] успешно обновлена";
        $this->createMessage = $this->createMessage ?: "Запись успешно создана";
        $message = $item->exists ? $this->updateMessage : $this->createMessage;
        $item->fill($data)->save();

        if (!empty($this->relations)) {
            foreach ($this->relations as $relation) {
                $item->$relation()->sync($data[$relation]);
            }
        }

        Alert::info($message);
        return redirect()->route($this->listRedirect, $this->redirectParams);
    }

    protected function removeItem(ProtoInterface $item)
    {
        // TODO настройка динамического сообщения об удалении
        if (!empty($this->relations)) {
            foreach ($this->relations as $relation) {
                $item->$relation()->detach();
            }
        }

        $title = !is_null($item->{$this->titleColumnName});
        $this->deleteMessage = $this->deleteMessage ?: "Запись [$item->id : $title] успешно удалена";

        $item->delete();
        $this->redirectTo();

        Alert::warning($this->deleteMessage);

        return redirect()->route($this->listRedirect, $this->redirectParams);
    }

    protected function redirectTo()
    {
        if ($this->redirectAfterUpdate) {
            $this->listRedirect = session()->has('listRedirect') ? session()->get('listRedirect') : $this->listRedirect;
            $this->redirectParams = session()->has('redirectParams') ? session()->get('redirectParams') : $this->redirectParams;
        }
    }
}
