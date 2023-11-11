<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunCommand extends Command
{
    protected $signature = 'aoc:run {year} {day} {--example}';

    protected $description = 'Run the solution';

    public function handle()
    {
        $instance = instanciate_solution($this->argument('year'), $this->argument('day'));
        if (! $instance) {
            $this->error('Solution not found');

            return;
        }

        $data = load_input($this->argument('year'), $this->argument('day'), $this->option('example'));
        if ($data == null) {
            $this->error('Input file does not exist');

            return;
        }

        $time = microtime(true);
        $silver = $instance->silver($data);
        $time = microtime(true) - $time;
        $this->info(sprintf('Silver (%.3fms): %s', $time * 1000, $silver));

        $time = microtime(true);
        $gold = $instance->gold($data);
        $time = microtime(true) - $time;
        $this->info(sprintf('Gold (%.3fms): %s', $time * 1000, $gold));
    }
}
