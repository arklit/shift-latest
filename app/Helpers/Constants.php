<?php

namespace App\Helpers;

class Constants
{
    public const DEFAULT_ITEMS_PER_PAGE = 10;
    public const ADMIN_GUARD = 'web';
    public const PHONE_REGEX = 'regex:~\+[0-9]{1}\s{1}\([0-9]{3}\)\s[0-9]{3}\-{1}[0-9]{2}\-{1}[0-9]{2}~';
    public const QUILL_TOOLS = ["text", "color", "header", "list", "format"];
}
