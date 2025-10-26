package solutions

import "core:fmt"
import "core:log"
import "core:strings"

import "../utils"

SOLVER_2015_03 :: utils.Solver {
	silver = proc(input: string) -> string {
		Position :: struct {
			x: int,
			y: int,
		}

		visited := make(map[Position]int)
		defer delete(visited)

		visited[Position{}] = 1

		x, y := 0, 0
		for r in input {
			switch r {
			case '>':
				x += 1
			case '<':
				x -= 1
			case '^':
				y += 1
			case 'v':
				y -= 1
			}
			visited[Position{x, y}] += 1
		}
		return fmt.aprintf("%d", len(visited))
	},
	gold = proc(input: string) -> string {
		Position :: struct {
			x: int,
			y: int,
		}

		visited := make(map[Position]int)
		defer delete(visited)

		visited[Position{}] = 2

		santa := Position{}
		robosanta := Position{}
		for r, i in input {
			switch r {
			case '>':
				if i % 2 == 0 do santa.x += 1
				else do robosanta.x += 1
			case '<':
				if i % 2 == 0 do santa.x -= 1
				else do robosanta.x -= 1
			case '^':
				if i % 2 == 0 do santa.y += 1
				else do robosanta.y += 1
			case 'v':
				if i % 2 == 0 do santa.y -= 1
				else do robosanta.y -= 1
			}

			if i % 2 == 0 do visited[santa] += 1
			else do visited[robosanta] += 1
		}
		return fmt.aprintf("%d", len(visited))
	},
}
