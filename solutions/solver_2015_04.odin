package solutions

import "core:fmt"
import "core:strings"

import "../utils"


SOLVER_2015_04 :: utils.Solver {
	silver = proc(input: string) -> string {
		input := strings.trim_space(input)
		pattern := []byte{0, 0, 0xF} // 5 leading zeroes

		decimal := 0
		for {
			secret := fmt.aprintf("%s%d", input, decimal)
			defer delete(secret)

			hash := utils.get_md5(secret)

			match := true
			for b, i in pattern {
				if hash[i] > b {
					match = false
					break
				}
			}

			if match {
				return fmt.aprintf("%d", decimal)
			}

			decimal += 1
		}
	},
	gold = proc(input: string) -> string {
		input := strings.trim_space(input)
		pattern := []byte{0, 0, 0} // 6 leading zeroes

		secret: [64]byte

		decimal := 0
		for {
			secret := fmt.bprintf(secret[:], "%s%d", input, decimal)

			hash := utils.get_md5(secret)

			match := true
			for b, i in pattern {
				if hash[i] > b {
					match = false
					break
				}
			}

			if match {
				return fmt.aprintf("%d", decimal)
			}

			decimal += 1
		}
	},
}
