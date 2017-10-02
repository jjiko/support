<?php

namespace Jiko\Support\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
  public function boot()
  {
    parent::boot();
  }

  public function map(Router $router)
  {
    require_once(__DIR__.'/../helpers.php');
    require_once(__DIR__.'/../Http/routes.php');
  }
}