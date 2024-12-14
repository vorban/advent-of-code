<div>
<img src="https://img.shields.io/badge/go-%2300ADD8.svg?style=for-the-badge&logo=go&logoColor=white">
<img src="https://img.shields.io/badge/total_stars%20⭐-034-fcd34d?style=for-the-badge">
<br/>
<div>
<img src="https://img.shields.io/badge/2015%20⭐-00-a8a29e">
<img src="https://img.shields.io/badge/2016%20⭐-00-a8a29e">
<img src="https://img.shields.io/badge/2017%20⭐-00-a8a29e">
<img src="https://img.shields.io/badge/2018%20⭐-00-a8a29e">
<img src="https://img.shields.io/badge/2019%20⭐-00-a8a29e">
<br>
<img src="https://img.shields.io/badge/2020%20⭐-00-a8a29e">
<img src="https://img.shields.io/badge/2021%20⭐-06-f4f4f5">
<img src="https://img.shields.io/badge/2022%20⭐-00-a8a29e">
<img src="https://img.shields.io/badge/2023%20⭐-00-a8a29e">
<img src="https://img.shields.io/badge/2024%20⭐-28-f4f4f5">
<br>

</div>
</div>
<!-- ----- marker: badges ----- -->

# advent-of-code

[Advent of code](https://adventofcode.com/) solutions in Go

## Compliance with AoC automation rules

This script/repo/tool does follow the automation guidelines on the [/r/adventofcode community wiki](https://www.reddit.com/r/adventofcode/wiki/faqs/automation).

Specifically:
- Outbound calls are made to `adventofcode.com/{year}` only.
- There is exactly 1 outbound call per year of advent of code, upon execution
  of the `updateBadges` script. Those calls are throttled to once every 2 seconds.
- **Results of all calls are cached** for the day in `cache/`
- The only way to bypass the cache is to manually delete it.
- The User-Agent header in `tools/updateBadges/scrap.go:fetchHtml()` is set to
  the .env variables (prompted if missing).

## Usage

```sh
go build -o bin ./cmd/solver.go
./bin/solver {year} {day} {silver|gold} [sample]

# if developing, you can simply run:
go run ./cmd/solver.go {year} {day} {silver|gold} [sample]
```

Both `year` and `day` should be left-padded with zeros (ex: `2024 03`).
Specify `sample` to run with the small example given in the subject.

### Discovering new solutions

When adding a solution (i.e. a `internal/{year}-{day}.go` file), you can run the discover tool
to modify your `cmd/solver.go` to discover it:

```sh
go build -o bin ./tools/discover
./bin/discover
```

### Initializing a new solution

You can also use the discover tool to initialize a new solution:
```sh
./bin/discover 2024 01
# creates internal/2024-01/main.go,t then updates cmd/solver.go
```

### Updating badges

You can update the badges in the readme with the following tool:

```sh
go build -o bin ./tools/updateBadges
./bin/updateBadges
# follow the instructions
```
