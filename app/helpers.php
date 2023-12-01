<?php

use GuzzleHttp\Client;

if (! function_exists('instanciate_solution')) {
    function instanciate_solution($year, $day)
    {
        $classname = sprintf('App\\Solutions\\Year_%s\\Solution_%s', $year, sprintf('%02d', $day));

        if (! class_exists($classname)) {
            return null;
        }

        return new $classname();
    }
}

if (! function_exists('load_input')) {
    function load_input($year, $day, bool $example = false): ?string
    {
        $data_filename = sprintf('resources/inputs/%s/%s.%s', $year, sprintf('%02d', $day), $example ? 'example' : 'txt');
        if (! file_exists($data_filename)) {
            return null;
        }

        return file_get_contents($data_filename);
    }
}

if (! function_exists('file_force_contents')) {
    function file_force_contents(string $filename, $data, int $flags = 0)
    {
        $dir = implode('/', explode('/', $filename, -1));
        if (! is_dir($dir)) {
            mkdir($dir);
        }
        file_put_contents($filename, $data, $flags);
    }
}

if (! function_exists('get_client_to_aoc_website')) {
    function get_client_to_aoc_website(string $url = null)
    {
        if (! $url) {
            $url = 'https://adventofcode.com/';
        }

        if (config('services.github.username') == null
            || config('services.github.email') == null
            || config('services.github.repository') == null) {
            throw new Exception('Please fill in your github username, email and repository in your .env file');
        }

        $user_agent = sprintf(
            'github.com/%s/%s by %s',
            config('app.github_username'),
            config('app.github_repository'),
            config('app.github_email')
        );

        return new Client([
            'base_uri' => $url,
            'headers' => [
                'User-Agent' => $user_agent,
                'Cookie' => 'session='.config('services.adventofcode.session_cookie'),
            ],
        ]);
    }
}

if (! function_exists('update_env_session_cookie')) {
    function update_env_session_cookie(string $session)
    {
        $env = file('.env', FILE_IGNORE_NEW_LINES);

        foreach ($env as $i => $line) {
            if (str_starts_with($line, 'AOC_SESSION_COOKIE')) {
                $env[$i] = "AOC_SESSION_COOKIE=$session";
                break;
            }
        }

        file_put_contents('.env', implode(PHP_EOL, $env));
    }
}
