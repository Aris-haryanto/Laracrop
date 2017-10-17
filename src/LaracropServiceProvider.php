<?php

namespace Arisharyanto\Laracrop;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Route;
use Blade;
use URL;

class LaracropServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/laracrop.php', 'laracrop'
        );
        $this->publishes([__DIR__.'/config' => config_path()], 'config');
        $this->publishes([__DIR__.'/plugins' => public_path('vendor/laracrop')], 'public');

        $this->setupRoutes($this->app->router);

        $baseUrl = URL::to('/');
        $uploadUrl = $baseUrl.'/'.config('laracrop.route_prefix').'/'.config('laracrop.upload_url');

        Blade::directive('laracropCss', function ($bootstrap = false) use ($baseUrl) {
            $addBootsrap = '';
            if($bootstrap){
                $addBootsrap = '<link rel="stylesheet" href="'.$baseUrl.'/vendor/laracrop/bootstrap/css/bootstrap.min.css">';
            }
            return $addBootsrap.'<link rel="stylesheet" href="'.$baseUrl.'/vendor/laracrop/jCrop/css/Jcrop.css">
                    <link rel="stylesheet" href="'.$baseUrl.'/vendor/laracrop/jCrop/css/demos.css">';
        });
        Blade::directive('laracropJs', function ($jQuery = false) use ($baseUrl) {
            $addjQuery = '';
            if($jQuery){
                $addjQuery = '<script src="'.$baseUrl.'/vendor/laracrop/jQuery/jquery-2.2.3.min.js"></script>';
            }
            return $addjQuery.'<script src="'.$baseUrl.'/vendor/laracrop/jCrop/js/Jcrop.js"></script>
                    <script src="'.$baseUrl.'/vendor/laracrop/laracrop.js"></script>';
        });
        Blade::directive('laracrop', function ($option) use ($baseUrl, $uploadUrl) {

            $newArr = array();
            $option = preg_replace('/\s+/', '', $option);
            if (preg_match('/\[|]/', $option)){

                $arr = explode('|', $option);
                foreach ($arr as $data) {
                    $exp = explode('=', $data);
                    $newArr[$exp[0]] = $exp[1];
                }
                
            }else{
                $newArr['name'] = $option;
            }
            $newArr = (object) $newArr;

            $name = $newArr->name;
            $aspectratio    = isset($newArr->aspectratio) ? $newArr->aspectratio : config('laracrop.aspectratio');
            $minsize        = isset($newArr->minsize) ? $newArr->minsize : config('laracrop.minsize');
            $maxsize        = isset($newArr->maxsize) ? $newArr->maxsize : config('laracrop.maxsize');
            $bgcolor        = isset($newArr->bgcolor) ? $newArr->bgcolor : config('laracrop.bgcolor');
            $bgopacity      = isset($newArr->bgopacity) ? $newArr->bgopacity : config('laracrop.bgopacity');

            return '<div class="form-group showimage">
                      <label for="exampleInputEmail1">Image</label>
                      <input type="file" class="cropimage" name="'.$name.'"
                        data-uploadurl="'.$uploadUrl.'"
                        data-aspectratio="'.$aspectratio.'"
                        data-minsize="'.$minsize.'"
                        data-maxsize="'.$maxsize.'"
                        data-bgcolor="'.$bgcolor.'"
                        data-bgopacity="'.$bgopacity.'" 
                        class="form-control">
                    </div>';
        });
    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Arisharyanto\Laracrop'], function ($router) {
            Route::group(['prefix' => config('laracrop.route_prefix')], function () {
                Route::post(config('laracrop.upload_url'), 'Laracrop@uploadAjax');
            });
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
