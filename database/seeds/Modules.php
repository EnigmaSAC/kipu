<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class Modules extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        $exclude = [
            // Add module aliases here that should not be installed globally
        ];

        $modules = module()->all();

        foreach ($modules as $module) {
            $alias = $module->getAlias();

            if (in_array($alias, $exclude)) {
                continue;
            }

            Artisan::call('module:install', [
                'alias'     => $alias,
                'company'   => $company_id,
                'locale'    => session('locale', company($company_id)->locale),
            ]);
        }
    }
}
