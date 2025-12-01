package solutions

import "core:fmt"
import "core:strconv"
import "core:log"
import "core:strings"

import "../utils"

SOLVER_2025_01 :: utils.Solver {
	silver = proc(input: string) -> string {
		lines, _ := strings.split(input, "\n")
		defer delete(lines)

		count := 0
		cursor := 50
		for line in lines {
			if len(line) == 0 do continue
			letter := line[0]
			amount, _ := strconv.parse_int(line[1:])

			switch letter {
			case 'L': cursor -= amount
			case 'R': cursor += amount
			}

			if cursor < 0 do cursor += 100
			cursor %= 100

			if cursor == 0 {
				count += 1
			}
		}

		return fmt.aprintf("%d", count)
	},
	gold = proc(input: string) -> string {
		lines, _ := strings.split(input, "\n")
		defer delete(lines)

		count := 0
		cursor := 50
		for line, i in lines {
			if len(line) == 0 do continue
			letter := line[0]
			amount, _ := strconv.parse_int(line[1:])

			log.infof("[%d] Moving %d by %c%d", i, cursor, letter, amount)
			// fmt.printf("[%d] Moving %d by %c%d\n", i, cursor, letter, amount)

			prev_cursor := cursor
			capped_amount := amount % 100
			wraps := amount / 100
			
			log.infof("[%d] Wrapped %d times", i, wraps)

			switch letter {
			case 'L': cursor -= capped_amount
			case 'R': cursor += capped_amount
			}

			wrapped := false
			if cursor < 0 {
				cursor += 100
				if prev_cursor != 0 {
					wraps += 1
					wrapped = true
					log.infof("[%d] Wrapped below 0 @", i)
				}
			}

			if cursor > 99 {
				cursor -= 100
				if prev_cursor != 0 {
					wraps += 1
					wrapped = true
					log.infof("[%d] Wrapped above 99 @", i)
				}
			}

			if cursor == 0 && prev_cursor != 0 && !wrapped {
				log.infof("[%d] Landed on 0 @", i)
				count += 1
			}

			count += wraps
		}

		// 3194 is too low
		return fmt.aprintf("%d", count)
	},
}
