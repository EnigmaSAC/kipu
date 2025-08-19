<?php

namespace Modules\DynamicDocumentNotes\Http\ViewComposers\Components\Documents\Form;

use App\Models\Banking\Account as Model;
use Illuminate\View\View;

class Note
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return mixed
     */
    public function compose(View $view)
    {
        $path = $view->getName();

        $accounts = $this->getAccounts();

        $view->with(['accounts' => $accounts]);

        // Change module path..
        $view->setPath(view('dynamic-document-notes::' . $path)->getPath());
    }

    protected function getAccounts()
    {
        return Model::enabled()->orderBy('name')->get()->pluck('title', 'id');
    }
}
