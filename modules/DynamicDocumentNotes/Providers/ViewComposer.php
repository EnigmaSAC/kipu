<?php

namespace Modules\DynamicDocumentNotes\Providers;

use Illuminate\Support\ServiceProvider;
use View;

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
        View::composer([
            'components.documents.form.note',
        ], 'Modules\DynamicDocumentNotes\Http\ViewComposers\Components\Documents\Form\Note');
    }
}
