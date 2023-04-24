<?php

    namespace App\Orchid\Screens\Seo;

    use App\Enums\OrchidRoutes;
    use App\Orchid\Abstractions\EditScreenPattern;
    use App\Orchid\Traits\CommandBarUndelitableTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use Orchid\Screen\Fields\Code;
    use Orchid\Support\Facades\Alert;
    use Orchid\Support\Facades\Layout;

    class RobotsScreen extends EditScreenPattern
    {
        public string $name = 'Редактирование файла robots.txt';
        protected string $file = '/robots.txt';

        use CommandBarUndelitableTrait;

        public function __construct()
        {
            $this->listRedirect = OrchidRoutes::robot->edit();
        }

        public function layout(): iterable
        {
            return [
                Layout::rows([
                    Code::make('robots')->language(Code::MARKUP)->height('1000px'),
                ]),
            ];
        }

        public function query()
        {
//            $f = Storage::disk('public')->path($this->file);

//            dd(file_exists($f));
            $fileContent = Storage::disk('public')->get($this->file);

            return [
                'robots' => $fileContent ?? '',
            ];
        }

        public function save(Request $request)
        {
            $data = $request->input('robots');
            Storage::disk('public')->put($this->file, $data);
            Alert::success('Файл robots.txt успешно обновлён');
        }


    }
