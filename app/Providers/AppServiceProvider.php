<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @destroy directiva blade
         * Se usa en la vista como @destroy(route('ruta.destroy', $modelo))
         */
        Blade::directive('destroy', function($url_destroy){
         $url_destroy_despues_primer_comilla = Str::after($url_destroy, "'");
         $url_destroy_antes_segunda_comilla = Str::before($url_destroy_despues_primer_comilla, "'");
         $permiso = $url_destroy_antes_segunda_comilla;
         $permiso_asterisco = substr($permiso, 0, strpos($permiso, '.')).'.*';

         return "<?php
         if(auth()->user()->can('".$permiso."') || auth()->user()->can('".$permiso_asterisco."') || auth()->user()->can('Universal.*')){
             echo e(BootForm::open(['class'=>'eliminar','method'=>'delete','url'=>".$url_destroy.",'onSubmit'=>'return confirm(\"¿Desea eliminar el registro?\")']) );
             echo e(BootForm::button('<i class=\"tio-delete tio-lg text-danger\"></i>',['type'=>'submit','class'=>'btn btn-link']));
             echo e(BootForm::close());
         }
         ?>";
        });
    }
}
