<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Accounts::class);
        $this->call(Categories::class);
        $this->call(Currencies::class);
        $this->call(EmailTemplates::class);
        $this->call(Modules::class);
        $this->call(Reports::class);
        $this->call(Settings::class);
    }
}
