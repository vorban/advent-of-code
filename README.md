<!-- AOCR_BADGES_START -->
<div>
<img src="https://img.shields.io/badge/total_stars%20⭐-000%2F500-fcd34d?style=for-the-badge">
<br>
<img src="https://img.shields.io/badge/2015%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2016%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2017%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2018%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2019%20⭐-00%2F50-a8a29e">
<br>
<img src="https://img.shields.io/badge/2020%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2021%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2022%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2023%20⭐-00%2F50-a8a29e">
<img src="https://img.shields.io/badge/2024%20⭐-00%2F50-a8a29e">
</div>

<!-- AOCR_BADGES_END -->

# advent-of-code

Advent of Code solutions in Odin

## Running the solvers

```sh
Usage:
        advent-of-code.exe [year] [day] [-gold] [-silver]
        odin run . -- [year] [day] [-gold] [-silver]
Flags:
        -year:<string>  | Year.
        -day:<string>   | Day.
                        |
        -gold           | Run gold versions.
        -silver         | Run silver versions.
```

## Running tests

```sh
odin test .
```

## Bootstrapping the solution for a given day

```sh
# creates `solutions/solver_YYYY_DD.odin`
make init YEAR=YYYY DAY=DD
```
