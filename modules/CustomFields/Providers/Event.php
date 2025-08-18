<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\CustomFields\Traits\CustomFields;

class Event extends ServiceProvider
{
    use CustomFields;

    /**
     * The event listener mappings for the module.
     *
     * @var array
     */
    protected $listen = [
        'cloner::cloned: App\Models\Document\Document' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
        'cloner::cloned: App\Models\Document\DocumentItem' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
        'cloner::cloned: App\Models\Common\Company' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
        'cloner::cloned: App\Models\Common\Item' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
        'cloner::cloned: App\Models\Common\Contact' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
        'cloner::cloned: App\Models\Banking\Account' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
        'cloner::cloned: App\Models\Banking\Transfer' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
        'cloner::cloned: App\Models\Banking\Transaction' => [
            \Modules\CustomFields\Listeners\CloneField::class,
        ],
    ];

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        return;

        $classes = array_keys($this->getCustomFieldsConfigInModules());

        foreach ($classes as $class) {
            $this->listen['cloner::cloned: ' . $class] = [
                \Modules\CustomFields\Listeners\CloneField::class,
            ];
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}
