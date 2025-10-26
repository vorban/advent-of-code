package solutions

import "../utils"
import "core:fmt"

SOLVER_2015_01 :: utils.Solver {
	silver = proc(input: string) -> string {
		floor := 0

		for r in input {
			if r == '(' {
				floor += 1
			} else {
				floor -= 1
			}
		}
		return fmt.aprintf("%d", floor)
	},
	gold = proc(input: string) -> string {
		floor := 0

		for r, i in input {
			if r == '(' {
				floor += 1
			} else {
				floor -= 1
			}

			if floor < 0 {
				return fmt.aprintf("%d", i + 1)
			}
		}

		return fmt.aprintf("%d", -1)
	},
}
