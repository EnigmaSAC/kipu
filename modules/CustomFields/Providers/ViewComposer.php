<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $views = $this->getViews('crud');

        View::composer($views, 'Modules\CustomFields\Http\ViewComposers\Field');

        $views = $this->getViews('show');

        View::composer($views, 'Modules\CustomFields\Http\ViewComposers\FieldShow');
    }

    public function getViews($type = 'crud'): array
    {
        $views = [];

        $config = config('custom-fields');

        $configs = collect($config);

        $configs->flatten(1)
            ->each(function ($item) use ($type, &$views) {
                $view_items = $item['views'][$type] ?? null;

                if ($view_items) {
                    $views = array_merge($views, $view_items);
                }
            });

        return $views;
    }
}
