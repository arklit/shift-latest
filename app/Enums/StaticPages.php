<?php

namespace App\Enums;

use App\Orchid\Layouts\StaticPages\AboutCompanyPageLayout;
use App\Orchid\Layouts\StaticPages\ContactsPageLayout;
use App\Orchid\Layouts\StaticPages\CooperationPageLayout;

enum StaticPages: string
{
    case PAGE_ABOUT = 'about';
    case PAGE_CONTACTS = 'contacts';
    case PAGE_COOPERATION = 'cooperation';


    public function getLayout()
    {
        return match ($this->value) {
            self::PAGE_ABOUT->value => new AboutCompanyPageLayout(),
            self::PAGE_CONTACTS->value => new ContactsPageLayout(),
            self::PAGE_COOPERATION->value => new CooperationPageLayout(),
        };
    }

    public function getTemplate()
    {
        return match ($this->value) {
            self::PAGE_ABOUT->value => 'pages.about',
            self::PAGE_CONTACTS->value => 'pages.contacts',
            self::PAGE_COOPERATION->value => 'pages.cooperation',
        };
    }

    public function getTitle()
    {
        return match ($this->value) {
            self::PAGE_ABOUT->value => 'О нас',
            self::PAGE_CONTACTS->value => 'Контакты',
            self::PAGE_COOPERATION->value => 'Сотрудничество',
        };
    }

    public static function getOptions(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->getTitle();
        }
        return $array;
    }

}
