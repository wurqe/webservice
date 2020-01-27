<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;

use Illuminate\Support\Facades\Validator;
use \Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

use App\Observers\InvitationObserver;
use App\Invitation;
use App\Observers\WorkObserver;
use App\Work;
use App\Observers\ServiceApplicationObserver;
use App\ServiceApplication;

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
      Invitation::observe(InvitationObserver::class);
      Work::observe(WorkObserver::class);
      ServiceApplication::observe(ServiceApplicationObserver::class);

      Validator::extend('imageable', function ($attribute, $value, $params, $validator) {
        if (!$value) return true;
        try {
            Image::make($value);
            return true;
        } catch (\Exception $e) {
          $validator->errors()->add('image', 'You should provide a valid image type');
          throw new ValidationException($validator);
        }
      });

      // Schema::defaultStringLength(191);
      $this->commands([
        InstallCommand::class,
        ClientCommand::class,
        KeysCommand::class,
      ]);
    }
}
