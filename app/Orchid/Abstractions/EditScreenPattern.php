<?php

namespace App\Orchid\Abstractions;

use App\Enums\OrchidRoutes;
use App\Interfaces\ProtoInterface;
use Illuminate\Support\Facades\Route;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;
use function redirect;
use function session;

abstract class EditScreenPattern extends Screen
{
    /** Название страницы редактирования. Задаётся вручную или же автоматически подставляется как Updated/Created
     * @var string */
    public string $name = '';

    /** Переменная определяющая редактируется ли уже существующая запись или создаётся новая
     * @var bool */
    protected bool $exists = false;

    /** Имя роута, на который будет происходить редирект после манипуляций с записью
     * @var string|null */
    protected ?string $redirectTo = null;

    /** Параметры для редиректа к списку моделей (номер страницы)
     * @var array */
    protected array $redirectParams = [];

    /** Если true, то произойдёт редирект на основе данных из сессии, если false, то маршрут для перенаправления будет
     * взят из дефолтного значения переменной
     * @var bool */
    protected bool $redirectAfterUpdate = true;

    /** Если true, то произойдёт редирект на основе данных из сессии, если false, то маршрут для перенаправления будет
     * взят из дефолтного значения переменной
     * @var bool */
    protected bool $redirectAfterDelete = true;

    /** Определяет дефолтное значение сообщения об успешном создании записи
     * @var string */
    protected string $createMessage = 'Запись успешно создана';

    /** Определяет дефолтное значение сообщения об успешном редактировании записи
     * @var string */
    protected string $updateMessage = 'Запись успешно обновлена';

    /** Определяет дефолтное значение сообщения об успешном удалении записи
     * @var string */
    protected string $deleteMessage = 'Запись успешно удалена';

    /** Определяет дефолтное значение заголовка для редактирования записи
     * @var string */
    protected string $updateTitle = 'Редактирование записи';

    /** Определяет дефолтное значение заголовка для удаления записи
     * @var string */
    protected string $createTitle = 'Создание записи';

    /** Имя свойства (колонки в БД) у редактируемой сущности, в котором хранится её название (title, name, etc.)
     * @var string */
    protected string $titleColumnName = 'title';

    /** Enum в котором хранятся данные по именам роутов для админки
     * @var OrchidRoutes */
    protected OrchidRoutes $route;

    /** Список отношений для синхронизации с текущей моделью
     * @var array */
    protected array $relations = [];

    /** Переменная для генерации хлебных крошек
     * @var bool */
    protected bool $makeBreadcrumbs = true;


    protected function queryMake(ProtoInterface $item)
    {
        $this->redirectAfterQuery();
        $this->exists = $item->exists;
        $name = $this->exists ? $this->updateTitle : $this->createTitle;

        if ($this->makeBreadcrumbs) {
            $currentRoute = $this->exists ? $this->route->edit() : $this->route->create();
            Breadcrumbs::for($currentRoute, fn(Trail $t) => $t->parent($this->route->list())->push($name, route($currentRoute, $item->id)));
        }

        if (empty($this->name)) {
            $this->name = $item->exists ? $this->updateTitle : $this->createTitle;
        }

        return [
            'item' => $item,
        ];
    }

    protected function saveItem(ProtoInterface $item, $data)
    {
        $this->redirectAfterUpdate($item);

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
        return redirect()->route($this->redirectTo, $this->redirectParams);
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
        $this->redirectAfterDelete();
        Alert::warning($this->deleteMessage);
        return redirect()->route($this->listRedirect);
    }

    protected function redirectAfterUpdate(ProtoInterface $item)
    {
        if ($this->redirectAfterUpdate) {
            $this->redirectTo = $this->route->edit();
            $this->redirectParams = ['id' => $item->id];
        }
    }

    protected function redirectAfterDelete()
    {
        if ($this->redirectAfterDelete) {
            if (Route::has($this->route->list())) {
                $this->redirectTo = $this->route->list();
            } else {
                $this->redirectTo = 'platform.main';
            }
        }
    }

    protected function redirectAfterQuery()
    {
        if (Route::has($this->route->list())) {
            $this->redirectTo = $this->route->list();
        } else {
            $this->redirectTo = 'platform.main';
        }
//        if ($this->redirectAfterUpdate) {
//            $this->listRedirect = session()->has('listRedirect') ? session()->get('listRedirect') : $this->listRedirect;
//            $this->redirectParams = session()->has('redirectParams') ? session()->get('redirectParams') : $this->redirectParams;
//        }
    }
}
