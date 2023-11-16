<div>
<div>
<img alt="Laravel"
src="https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white">
<img alt="Total Stars: 40"
src="https://img.shields.io/badge/total_stars%20⭐-40-fcd34d?style=for-the-badge">
</div>
<br />
<img alt="2015: 0"
src="https://img.shields.io/badge/2015%20⭐-0-a8a29e">
<img alt="2016: 0"
src="https://img.shields.io/badge/2016%20⭐-0-a8a29e">
<img alt="2017: 0"
src="https://img.shields.io/badge/2017%20⭐-0-a8a29e">
<img alt="2018: 0"
src="https://img.shields.io/badge/2018%20⭐-0-a8a29e">
<img alt="2019: 4"
src="https://img.shields.io/badge/2019%20⭐-4-f4f4f5">
<br />
<img alt="2020: 0"
src="https://img.shields.io/badge/2020%20⭐-0-a8a29e">
<img alt="2021: 26"
src="https://img.shields.io/badge/2021%20⭐-26-f4f4f5">
<img alt="2022: 10"
src="https://img.shields.io/badge/2022%20⭐-10-f4f4f5">
<img alt="2023: 0"
src="https://img.shields.io/badge/2023%20⭐-0-a8a29e">
</div>
<br />

# Advent of Code

Advent of Code solutions.

Released under the MIT License.
See <a href="./LICENSE">./LICENSE</a>.

Copyright :copyright: 2023 Valentin Orban

## Installation

First, click on "use this template" and generate a new repo based on this one.

```sh
git clone git@github.com:vorban/advent-of-code.git
cd advent-of-code

cp .env.example .env
```

### Using docker ?

```sh
docker run --rm --interactive --tty --volume $PWD:/app composer install

vendor/bin/sail up -d
echo Enjoy!
```

### Got a local dev environment ?

```sh
composer install
echo Enjoy!
```

## Usage

Use `sail` or `php` depending on wether you want to use docker or not.

```sh
# generate code file and download input
vendor/bin/sail artisan aoc:prepare {year} {day}

# hopefully first try !
vendor/bin/sail artisan aoc:run {year} {day} {--example}

# once you're done for the day
vendor/bin/sail artisan aoc:update-badges
```
