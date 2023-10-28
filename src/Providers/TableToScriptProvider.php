<?php

namespace Ciber2018\Tabletoscript\Providers;

use Illuminate\Support\ServiceProvider;

class TableToScriptProvider extends ServiceProvider{
    
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views','tabletoscript');
    }
}