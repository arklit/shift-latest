<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class ConfiguratorService
{
    protected Collection $configs;
    protected array $keys = [];
    protected array $casting = [];

    public function __construct(Collection $configs)
    {
        $this->configs = $configs;
        $this->keys = $this->configs->pluck('key')->toArray();
    }

    public function getData(string $key)
    {
        if (!in_array($key, $this->keys, true)) {
            return null;
        }

        $config = $this->configs->firstWhere('key', '=', $key);

        if (!$config) {
            return null;
        }

        return $config->value;
    }

    public function getKeysList()
    {
        return $this->keys;
    }
}
