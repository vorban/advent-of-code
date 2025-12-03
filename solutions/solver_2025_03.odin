package solutions

import "core:fmt"
import "core:strings"
import "core:log"
import "core:strconv"
import "core:math"

import "../utils"

recurse_2025_03 :: proc(bank: []u8, size: int) -> uint {
	max : u8 = '0'
	maxi := 0
	
	length := len(bank)
	for c, i in bank {
		if i > len(bank) - size do break
		if max < c {
			max = c
			maxi = i
		}
	}
	
	max -= '0' // convert ascii codepoint to decimal value

	if size == 1 do return uint(max)

	shift := uint(math.pow(10.0, f64(size - 1)))

	return uint(max) * shift + recurse_2025_03(bank[maxi + 1:], size - 1)
}

SOLVER_2025_03 :: utils.Solver {
    silver = proc(input: string) -> string {
	banks, _ := strings.split(input, "\n")
	defer delete(banks)

	sum : uint = 0
	
	for bank in banks {
		if len(bank) == 0 do continue
		sum += recurse_2025_03(transmute([]u8)bank, 2)
	}

        return fmt.aprintf("%d", sum)
    },
    gold = proc(input: string) -> string {
	banks, _ := strings.split(input, "\n")
	defer delete(banks)

	sum : uint = 0
	
	for bank in banks {
		if len(bank) == 0 do continue
		sum += recurse_2025_03(transmute([]u8)bank, 12)
	}

        return fmt.aprintf("%d", sum)
    },
}
