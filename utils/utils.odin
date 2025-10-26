package utils

import "core:fmt"
import "core:os"
import "core:strings"
import "core:time"
Solver :: struct {
	silver: proc(s: string) -> string,
	gold:   proc(s: string) -> string,
}

get_first_year :: proc() -> int {
	return 2015
}

get_last_year :: proc() -> int {
	now := time.now()
	if time.month(now) == time.Month.December {
		return time.year(now)
	}

	return time.year(time.now()) - 1
}

get_last_day :: proc(year: int) -> int {
	if year < 2025 {
		return 25
	}

	return 12
}

run_timed_solver :: proc(solver: proc(_: string) -> string, input: string) -> (string, int) {
	now := time.now()
	solution := solver(input)
	st := time.duration_milliseconds(time.diff(now, time.now()))

	return solution, int(st)
}

get_input_file :: proc(key: string) -> string {
	key := fmt.aprintf("./input/%s.txt", key)
	input_file, ok := os.read_entire_file_from_filename(key)

	if (!ok) do return string("")

	return string(input_file)
}
