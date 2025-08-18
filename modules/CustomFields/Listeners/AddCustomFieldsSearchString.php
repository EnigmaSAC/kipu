<?php

namespace Modules\CustomFields\Listeners;

use App\Events\Common\SearchStringApplying;
use Lorisleiva\LaravelSearchString\AST\SoloSymbol;
use Lorisleiva\LaravelSearchString\Exceptions\InvalidSearchStringException;
use Lorisleiva\LaravelSearchString\SearchStringManager;
use Modules\CustomFields\Traits\CustomFields;

class AddCustomFieldsSearchString
{
    use CustomFields;

    /**
     * Handle the event.
     *
     * @param  SearchStringApplying $event
     *
     * @return void
     */
    public function handle(SearchStringApplying $event)
    {
        $request = request();

        if (is_null($search = $request->get('search'))) {
            return;
        }

        try {
            $ast = (new SearchStringManager($event->query->getModel()))->parse($search);
        } catch (InvalidSearchStringException $ex) {
            logs()->warning("Search parameter could not be parsed: (Search parameter value: '{$search}'; Error Message: {$ex->getMessage()})");
            return;
        }

        $condition = false;

        if (in_array(get_class($event->query->getModel()), array_keys(config('custom-fields')))) {
            $code = $request->segment(2) . '-' . $request->segment(3);

            // This line will be change
            if ($code === 'banking-transactions' && is_null($request->type)) {
                $condition = $this->getFieldsByLocation($code, 'income')->isNotEmpty();

                if (!$condition) {
                    $condition = $this->getFieldsByLocation($code, 'expense')->isNotEmpty();
                }
            } else {
                $condition = $this->getFieldsByLocation($code, $request->type)->isNotEmpty();
            }
        }

        if (! $condition || str($search)->contains(':')) {
            return;
        }

        if (isset($ast->expressions)) {
            foreach ($ast->expressions as $expression) {
                if ($expression instanceof SoloSymbol) {
                    $text_search = '"' . $expression->content . '"';
                    $replaced = "({$text_search} or custom_fields:({$expression->content}))";
                    session(['custom_fields_search_string_replaced' => $replaced]);

                    $search = str_replace($text_search, $replaced, $search);

                    $request->merge(['search' => $search]);
                }
            }
        } else {
            $replaced = " or custom_fields:({$search})";
            session(['custom_fields_search_string_replaced' => $replaced]);

            $search .= $replaced;

            $request->merge(['search' => $search]);
        }
    }
}
