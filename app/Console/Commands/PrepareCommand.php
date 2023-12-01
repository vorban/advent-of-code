<?php

namespace App\Console\Commands;

use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PrepareCommand extends Command
{
    public const MAX_EXAMPLE_LENGTH = 74;

    protected $signature = 'aoc:prepare {year} {day}';

    protected $description = 'Prepare the input file and the example file';

    protected int $year;

    protected int $day;

    public function handle()
    {
        $this->year = intval($this->argument('year'));
        $this->day = intval($this->argument('day'));

        if (config('services.adventofcode.session_cookie') === '') {
            $sessionCookie = $this->secret('Please copy paste your session cookie');
            $this->info('Updating .env file...');
            update_env_session_cookie($sessionCookie);
            config(['services.adventofcode.session_cookie' => $sessionCookie]);
        }

        $this->handleInputFile();
        $this->handleExample();
        $this->handleStub();
    }

    private function handleInputFile()
    {
        $filename = sprintf('resources/inputs/%d/%s.txt', $this->year, sprintf('%02d', $this->day));
        $cache_filename = sprintf('inputs/%d/%s.txt', $this->year, sprintf('%02d', $this->day));
        if (file_exists($filename)) {
            $this->info('Input file already exists, skipping...');

            return;
        }

        if (Storage::exists($cache_filename)) {
            $this->info('Getting input file from cache...');
            $content = Storage::get($cache_filename);
            $this->info("-> Creating input file [$filename].");
            file_force_contents($filename, $content);

            return;
        }

        $url = sprintf('%d/day/%d/input', $this->year, $this->day);
        $this->info("Fetching input file from [$url]...");
        $content = get_client_to_aoc_website()->get($url)->getBody()->getContents();

        $this->info('-> Caching input file...');
        Storage::put($cache_filename, $content);

        $this->info("-> Creating input file [$filename].");
        file_force_contents($filename, $content);
    }

    private function handleExample()
    {
        $filename = sprintf('resources/inputs/%s/%s.example', $this->year, sprintf('%02d', $this->day));
        $cache_filename = sprintf('inputs/%s/%s.example', $this->year, sprintf('%02d', $this->day));

        if (file_exists($filename)) {
            $this->info('Example file already exists, skipping...');

            return;
        }

        if (Storage::exists($cache_filename)) {
            $this->info('Getting example file from cache...');
            $content = Storage::get($cache_filename);
            $this->info("-> Creating example file [$filename].");
            file_force_contents($filename, $content);

            return;
        }

        $data = null;

        if (Storage::exists($cache_filename)) {
            $this->info('Getting example file from cache...');
            $data = Storage::get($cache_filename);
        } else {
            $url = sprintf('%s/day/%s', $this->year, $this->day);
            $this->info("Fetching [$url] for example...");
            $data = get_client_to_aoc_website()->get($url)->getBody()->getContents();
            Storage::put($cache_filename, $data);
        }

        $this->info('Searching for example...');
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        libxml_clear_errors();

        $code = $dom->getElementsByTagName('code');
        if ($code->length == 0) {
            $this->error('-> No example found.');

            return;
        }

        $code_tag_index = 0;
        if ($code->length > 1) {
            if (! $this->confirm('-> Multiple <code /> tags found. You will choose which one to use.', true)) {
                $this->info('-> Skipping example...');

                return;
            }
            $samples = [];
            foreach ($code as $i => $element) {
                $str = str_replace("\n", ' ', $element->textContent);
                if (strlen($str) > static::MAX_EXAMPLE_LENGTH) {
                    $cut = substr($str, 0, static::MAX_EXAMPLE_LENGTH);
                    $samples[$i] = sprintf('"%s[... +%d chars]"', $cut, strlen($str) - strlen($cut));
                } else {
                    $samples[$i] = sprintf('"%s"', $str);
                }
            }
            $code_tag_index = array_search($this->choice('Please tell us which one to use?', $samples), $samples);
        }

        $content = $code->item($code_tag_index)->textContent;

        $this->info("-> Creating example file [$filename]...");
        file_force_contents($filename, $content);
    }

    private function handleStub()
    {
        $filename = sprintf('app/Solutions/Year_%s/Solution_%s.php', $this->year, sprintf('%02d', $this->day));
        if (file_exists($filename)) {
            $this->info('Solution file already exists, skipping...');

            return;
        }

        $this->info('Preparing solution from stub...');

        $stub = file_get_contents('app/Solutions/Solution.stub');
        $stub = str_replace('{{ $year }}', $this->year, $stub);
        $stub = str_replace('{{ $day }}', sprintf('%02d', $this->day), $stub);

        $this->info("-> Creating solution file [$filename]...");
        file_force_contents($filename, $stub);
    }
}
