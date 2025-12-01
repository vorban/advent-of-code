package solutions

import "../utils"
import "core:fmt"
import "core:strconv"
import "core:strings"

SOLVER_2015_02 :: utils.Solver {
	silver = proc(input: string) -> string {
		paper := 0

		lines, _ := strings.split(input, "\n")
		defer delete(lines)

		for line in lines {
			if line == "" do continue

			sides, _ := strings.split(line, "x")
			defer delete(sides)
			w, _ := strconv.parse_int(sides[0])
			l, _ := strconv.parse_int(sides[1])
			h, _ := strconv.parse_int(sides[2])

			min_area := min(l * w, w * h, h * l)
			paper += 2 * l * w + 2 * w * h + 2 * h * l + min_area
		}

		return fmt.aprintf("%d", paper)
	},
	gold = proc(input: string) -> string {
		ribbon := 0

		lines, _ := strings.split(input, "\n")
		defer delete(lines)

		for line in lines {
			if line == "" do continue

			sides, _ := strings.split(line, "x")
			defer delete(sides)
			w, _ := strconv.parse_int(sides[0])
			l, _ := strconv.parse_int(sides[1])
			h, _ := strconv.parse_int(sides[2])

			min_perimeter := min(2 * w + 2 * l, 2 * w + 2 * h, 2 * l + 2 * h)
			ribbon += min_perimeter + w * l * h
		}

		return fmt.aprintf("%d", ribbon)
	},
}
