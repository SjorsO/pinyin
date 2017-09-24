<?php

namespace SjorsO\Pinyin\Facades;

use Illuminate\Support\Facades\Facade;

class Pinyin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SjorsO\Pinyin\Pinyin::class;
    }
}
