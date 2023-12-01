<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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

        $files = array_filter(Storage::files('events/'), function (string $file) {
            if (preg_match('/\d{4}-\d{2}\d{2}\.html/', $file)) {
                return false;
            }

            $date = Carbon::createFromFormat('Y-m-d', basename($file, '.html'));
            if ($date->lte(Carbon::yesterday()->endOfDay())) {
                return false;
            }

            return true;
        });

        $content = '';
        if (count($files) > 0) {
            $this->info('Getting data from cache until [tomorrow]...');
            $content = Storage::get($files[0]);
        } else {
            $this->info('Fetching [https://adventofcode.com/events]...');
            $content = get_client_to_aoc_website()->get('events')->getBody()->getContents();

            $this->info('-> Creating cache file.');
            Storage::delete(Storage::files('events/'));
            Storage::put('events/'.Carbon::now()->format('Y-m-d').'.html', $content);
        }

        $years = [];
        $matches = [];
        preg_match_all('/<a href="\/.*">\[(\d{4})\].*>( ?\d+)/', $content, $matches);
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
