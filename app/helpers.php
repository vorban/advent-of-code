<?php

if (! function_exists('instanciate_solution')) {
    function instanciate_solution($year, $day)
    {
        $classname = sprintf('App\\Solutions\\Solution%s%s', $year, sprintf('%02d', $day));

        if (! class_exists($classname)) {
            return null;
        }

        return new $classname();
    }
}

if (! function_exists('load_input')) {
    function load_input($year, $day, bool $example = false): ?string
    {
        $data_filename = sprintf('resources/inputs/%s-%s.%s', $year, sprintf('%02d', $day), $example ? 'example' : 'txt');
        if (! file_exists($data_filename)) {
            return null;
        }

        return file_get_contents($data_filename);
    }
}
