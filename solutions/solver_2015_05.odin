package solutions

import "core:fmt"
import "core:log"
import "core:strconv"
import "core:strings"
import "core:unicode/utf8"

import "../utils"

SOLVER_2015_05 :: utils.Solver {
	silver = proc(input: string) -> string {
		lines, _ := strings.split(input, "\n")
		defer delete(lines)

		nice := 0

		forbidden := []string{"ab", "cd", "pq", "xy"}
		outer: for line in lines {
			vowel_count := 0
			double_letter_count := 0
			forbidden_count := 0

			for r, i in line {
				if utils.is_vowel(r) {
					vowel_count += 1
				}

				if i < len(line) - 1 {
					if line[i + 1] == u8(r) {
						double_letter_count += 1
					}
					concat := fmt.aprintf("%r%r", r, rune(line[i + 1]))
					defer delete(concat)

					for f in forbidden {
						if concat == f {
							forbidden_count += 1
						}
					}
				}
			}

			if vowel_count >= 3 && double_letter_count > 0 && forbidden_count == 0 {
				nice += 1
			}
		}

		return fmt.aprintf("%d", nice)
	},
	gold = proc(input: string) -> string {
		lines, _ := strings.split(input, "\n")
		defer delete(lines)

		nice := 0

		outer: for line in lines {
			letter_pairs := make(map[string]int) // "ab" => index of 'b' in line
			defer delete(letter_pairs)
			has_spaced_repeat := false
			has_non_overlapping_pair := false

			for r, i in line {
				if i < len(line) - 1 {
					concat := fmt.aprintf("%r%r", r, rune(line[i + 1]))
					defer delete(concat)

					if index, ok := letter_pairs[concat]; ok && index != i {
						has_non_overlapping_pair = true
					} else {
						letter_pairs[concat] = i + 1
					}
				}
				if i < len(line) - 2 {
					if u8(r) == line[i + 2] && u8(r) != line[i + 1] {
						has_spaced_repeat = true
					}
				}
			}

			if has_spaced_repeat && has_non_overlapping_pair {
				nice += 1
			}
		}

		return fmt.aprintf("%d", nice)
	},
}
