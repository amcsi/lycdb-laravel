<?php namespace Lycee\Providers;

use Illuminate\Support\ServiceProvider;

class ViewHelpersProvider extends ServiceProvider {

    /**
     * @var \Illuminate\Html\HtmlBuilder $html
     */
    private $html;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $html = $this->app->make('html');
        $this->html = $html;

        $this->markupToHtml();
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
	}

    private function markupToHtml()
    {
        $this->html->macro('markupToHtml', function ($markup) {

        });
    }


}
