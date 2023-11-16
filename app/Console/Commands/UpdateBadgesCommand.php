<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBadgesCommand extends Command
{
    protected $signature = 'aoc:update-badges';

    protected $description = 'Update badges in README.md';

    public function handle()
    {
        if (config('services.adventofcode.session_cookie') === '') {
            $sessionCookie = $this->secret('Please copy paste your session cookie');
            update_env_session_cookie($sessionCookie);
            config(['services.adventofcode.session_cookie' => $sessionCookie]);
        }

        $this->info('Fetching yearly data...');
        $content = get_client_to_aoc_website()->get('events')->getBody()->getContents();

        $years = [];
        $matches = [];
        preg_match_all('/<a href="\/(\d{4})">.*>( ?\d+)/', $content, $matches);
        foreach ($matches[0] as $key => $match) {
            $years[$matches[1][$key]] = intval($matches[2][$key]);
        }

        $years = collect($years);
        $yearsIterator = Carbon::create(2015, 12, 1)->toPeriod(Carbon::now()->addYear(), '1 year');
        $blade = view('commands.badges', compact('years', 'yearsIterator'))->render();
        $blade = trim(preg_replace('/\n\s+/', "\n", $blade));

        $readme = file_get_contents(base_path('README.md'));

        // remove badges
        $readme = preg_replace('/<div>(?>.|\n)*<\/div>/', '', $readme);
        $readme = $blade.$readme;

        if ($years->sum() > 0) {
            $this->info(sprintf('You\'ve got %d stars:', $years->sum()));
            foreach ($years->sortKeys() as $year => $stars) {
                $this->info(sprintf('- [%d] %02d', $year, $stars));
            }
        }
        $this->info('-> Updating badges in README.md.');

        file_put_contents(base_path('README.md'), $readme);
    }
}
