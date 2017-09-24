<?php

namespace SjorsO\TextFile\Providers;

use Illuminate\Support\ServiceProvider;
use SjorsO\Pinyin\Pinyin;

class PinyinProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(Pinyin::class, function($app) {
            return new \SjorsO\Pinyin\Pinyin();
        });
    }

    public function register()
    {
        //
    }
}
