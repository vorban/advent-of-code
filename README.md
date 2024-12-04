<div>
	<img src="https://img.shields.io/badge/go-%2300ADD8.svg?style=for-the-badge&logo=go&logoColor=white">
	<img src="https://img.shields.io/badge/total_stars%20⭐-012-fcd34d?style=for-the-badge">
<br/>
	<div>
		<img src="https://img.shields.io/badge/2015%20⭐-00-a8a29e">
		<img src="https://img.shields.io/badge/2016%20⭐-00-a8a29e">
		<img src="https://img.shields.io/badge/2017%20⭐-00-a8a29e">
		<img src="https://img.shields.io/badge/2018%20⭐-00-a8a29e">
		<img src="https://img.shields.io/badge/2019%20⭐-00-a8a29e">
		<br>
		<img src="https://img.shields.io/badge/2020%20⭐-00-a8a29e">
		<img src="https://img.shields.io/badge/2021%20⭐-04-f4f4f5">
		<img src="https://img.shields.io/badge/2022%20⭐-00-a8a29e">
		<img src="https://img.shields.io/badge/2023%20⭐-00-a8a29e">
		<img src="https://img.shields.io/badge/2024%20⭐-08-f4f4f5">
		<br>

	</div>
</div>
<!-- ----- marker: badges ----- -->





# advent-of-code

[Advent of code](https://adventofcode.com/) solutions in Go

## Usage

```sh
go build -o bin ./cmd/solver.go
./bin/solver {year} {day} {silver|gold} [sample]
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

### Updating badges

You can update the badges in the readme with the following tool:

```sh
go build -o bin ./tools/updateBadges
./bin/updateBadges
# follow the instructions
```
