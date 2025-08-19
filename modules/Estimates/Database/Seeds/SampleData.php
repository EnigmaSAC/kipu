<?php

namespace Modules\Estimates\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Modules\Estimates\Models\Estimate;

class SampleData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        $count = (int)$this->command->option('count');

        $company = (int) $this->command->option('company');

        Estimate::factory($count)->estimate()->company($company)->create();

        Model::unguard();
    }
}
