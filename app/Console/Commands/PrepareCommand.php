<?php

namespace App\Console\Commands;

use DOMDocument;
use DOMElement;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class PrepareCommand extends Command
{
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
            $this->updateEnvFile($sessionCookie);
            config(['services.adventofcode.session_cookie' => $sessionCookie]);
        }

        $this->handleInputFile();
        $this->handleExample();
        $this->handleStub();
    }

    private function handleInputFile()
    {
        $filename = sprintf('resources/inputs/%d/%s.txt', $this->year, sprintf('%02d', $this->day));
        if (file_exists($filename)) {
            $this->info('Input file already exists, skipping...');

            return;
        }

        $client = new Client();
        $url = sprintf('https://adventofcode.com/%d/day/%d/input', $this->year, $this->day);

        $content = $client->get($url, [
            'headers' => [
                'Cookie' => 'session='.config('services.adventofcode.session_cookie'),
            ],
        ])->getBody()->getContents();

        $this->info("Creating input file [$filename]...");
        file_force_contents($filename, $content);
    }

    private function handleExample()
    {
        $filename = sprintf('resources/inputs/%s/%s.example', $this->year, sprintf('%02d', $this->day));

        if (file_exists($filename)) {
            $this->info('Example file already exists, skipping...');

            return;
        }

        $client = new Client();
        $url = sprintf('https://adventofcode.com/%s/day/%s', $this->year, $this->day);

        $data = $client->get($url, [
            'headers' => [
                'Cookie' => 'session='.config('services.adventofcode.session_cookie'),
            ],
        ])->getBody()->getContents();

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        libxml_clear_errors();

        $pre = $dom->getElementsByTagName('pre');
        if ($pre->length == 0) {
            $this->error('No example found NO PRE');

            return;
        }

        $pre = $pre->item(0);
        if (! $pre instanceof DOMElement) {
            $this->error('No example found PRE NOT DOMELEMENT');

            return;
        }

        $code = $pre->getElementsByTagName('code');
        if ($code->length == 0) {
            $this->error('No example found NO CODE');

            return;
        }
        $content = $code->item(0)->textContent;

        $this->info("Creating example file [$filename]...");
        file_force_contents($filename, $content);
    }

    private function handleStub()
    {
        $filename = sprintf('app/Solutions/Year_%s/Solution_%s.php', $this->year, sprintf('%02d', $this->day));
        if (file_exists($filename)) {
            $this->info('Solution file already exists, skipping...');

            return;
        }

        $stub = file_get_contents('app/Solutions/Solution.stub');
        $stub = str_replace('{{ $year }}', $this->year, $stub);
        $stub = str_replace('{{ $day }}', sprintf('%02d', $this->day), $stub);

        $this->info("Creating solution file [$filename]...");
        file_force_contents($filename, $stub);
    }

    private function updateEnvFile(string $session)
    {
        $env = file('.env', FILE_IGNORE_NEW_LINES);

        foreach ($env as $i => $line) {
            if (str_starts_with($line, 'AOC_SESSION_COOKIE')) {
                $env[$i] = "AOC_SESSION_COOKIE=$session";
                break;
            }
        }

        $this->info('Updating .env file...');
        file_put_contents('.env', implode(PHP_EOL, $env));
    }
}
