<div>
<div>
<img alt="Laravel"
src="https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white">
<img alt="Total Stars: 53"
src="https://img.shields.io/badge/total_stars%20⭐-53-fcd34d?style=for-the-badge">
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
<img alt="2021: 29"
src="https://img.shields.io/badge/2021%20⭐-29-f4f4f5">
<img alt="2022: 10"
src="https://img.shields.io/badge/2022%20⭐-10-f4f4f5">
<img alt="2023: 10"
src="https://img.shields.io/badge/2023%20⭐-10-f4f4f5">
<img alt="2024: 0"
src="https://img.shields.io/badge/2024%20⭐-0-a8a29e">
<br />
</div>
<br />

# laravel-advent-of-code

Advent of Code solution repository.

Should you want to use my template repository, head to
[vorban/laravel-advent-of-code](https://github.com/vorban/laravel-advent-of-code).

**This is not made to be hosted.** This is a compilation
of my solutions to the Advent of Code, using Laravel and PHP.

Solutions are found in [app/Solutions](./app/Solutions/).

Released under the MIT License.
See [LICENSE](./LICENSE).

Copyright :copyright: 2023 Valentin Orban

## AoC compliance and usage of this template

This repository does follow the [automation guidelines](https://www.reddit.com/r/adventofcode/wiki/faqs/automation) on the [/r/adventofcode](https://www.reddit.com/r/adventofcode/) community wiki. Specifically:

- No automation is provided
- Outbound calls to the /events endpoint are cached for 24h (see `app/Console/UpdateBadgesCommand.php`)
- Once inputs are downloaded, they are cached locally indefinitly (see `app/Console/PrepareCommand`)
- The User-Agent header is set to me

This template repository is provided as-is,
as detailed in the [LICENSE](./LICENSE) file.

It is a showcase of my skills, not a project
that aims to be hosted.

Although a Laravel application, this code is not fit
to be hosted. Specifically, the code as-is is not fit
for any kind of automation or production environments.

## Installation

Go to [vorban/laravel-advent-of-code](https://github.com/vorban/laravel-advent-of-code) and follow instructions.

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
sail artisan aoc:prepare {year} {day}

# hopefully first try !
sail artisan aoc:run {year} {day} {--example}

# once you're done for the day
sail artisan aoc:update-badges
```
