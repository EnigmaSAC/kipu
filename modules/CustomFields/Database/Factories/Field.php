<?php

namespace Modules\CustomFields\Database\Factories;

use App\Abstracts\Factory;
use Modules\CustomFields\Models\Field as Model;
use Modules\CustomFields\Traits\CustomFields;
use Modules\CustomFields\Traits\Tests;

class Field extends Factory
{
    use CustomFields, Tests;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        list($type, $rule) = $this->getTypeAndRules();

        if (empty($rule)) {
            $rule = '';
        }

        $location = $this->faker->randomElement(array_keys($this->getLocations(false, true)));
        $sort = $this->faker->randomElement(array_keys($this->getSortOrders()[$location]));

        $paramenters = [
            'company_id'    => $this->company->id,
            'name'          => $this->faker->text(10),
            'type'          => $type,
            'rule'          => $rule,
            'location'      => $location,
            'sort'          => $sort,
            'order'         => $this->faker->randomElement(['input_start', 'input_end']),
            'width'         => $this->faker->randomElement(['16', '33', '50', '100']),
            'show'          => $this->faker->randomElement(['always', 'never', 'if_filled']),
        ];

        if (in_array($type, ['select', 'checkbox'])) {
            $paramenters['items'] = $this->getItems($this->faker->numberBetween(1, 20), $this->faker->boolean);
        }

        $paramenters['default'] = $this->faker->boolean ? $this->getValue($paramenters['type'], $paramenters['rule']) : null;

        return $paramenters;
    }

    /**
     * Determine the location of the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function location($location)
    {
        return $this->state([
            'location' => $location,
            'sort'     => $this->faker->randomElement(array_keys($this->getSortOrders()[$location]))
        ]);
    }

    /**
     * Indicate that the model is enabled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function enabled()
    {
        return $this->state([
            'enabled' => 1,
        ]);
    }

    /**
     * Indicate that the model is disabled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function disabled()
    {
        return $this->state([
            'enabled' => 0,
        ]);
    }
}
