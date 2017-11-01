<?php 

namespace Asdozzz\Vika;

use Illuminate\Support\ServiceProvider;

class VikaServiceProvider extends ServiceProvider
{

	public function register()
	{
	}

	public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/Filemakers/templates/', 'vika_filemakers');

		$this->publishes([
            __DIR__ . '/config/vika.php' => config_path('vika.php'),
        ]);
	}

}