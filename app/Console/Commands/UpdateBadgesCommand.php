<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class UpdateBadgesCommand extends Command
{
    protected $signature = 'aoc:update-badges';

    protected $description = 'Update badges in README.md';

    public function handle()
    {
        if (config('services.adventofcode.session_cookie') === '') {
            $sessionCookie = $this->secret('Please copy paste your session cookie');
            $this->updateEnvFile($sessionCookie);
            config(['services.adventofcode.session_cookie' => $sessionCookie]);
        }

        // ----- Fetch and parse leaderboard -----
        $url = sprintf('https://adventofcode.com/events');

        $client = new Client();
        $content = $client->get($url, [
            'headers' => [
                'Cookie' => 'session='.config('services.adventofcode.session_cookie'),
            ],
        ])->getBody()->getContents();

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

        // ----- Update README.md -----
        $readme = file_get_contents(base_path('README.md'));

        // remove badges
        $readme = preg_replace('/<div>(?>.|\n)*<\/div>/', '', $readme);
        $readme = $blade.$readme;

        $this->info('Updating README.md');

        file_put_contents(base_path('README.md'), $readme);
    }
}
