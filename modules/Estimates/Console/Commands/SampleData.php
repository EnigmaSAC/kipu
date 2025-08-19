<?php

namespace Modules\Estimates\Console\Commands;

use Modules\Estimates\Database\Seeds\SampleData as SampleDataSeeder;
use Illuminate\Console\Command;

class SampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estimates-sample-data:seed  {--count=100 : total records for each item} {--company=1 : the company id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed for sample data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $class = $this->laravel->make(SampleDataSeeder::class);

        $seeder = $class->setContainer($this->laravel)->setCommand($this);

        $seeder->__invoke();
    }
}
