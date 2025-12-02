package solutions

import "core:fmt"
import "core:strings"
import "core:log"
import "core:strconv"
import "core:math"

import "../utils"

SOLVER_2025_02 :: utils.Solver {
    silver = proc(input: string) -> string {
	str_ranges, _ := strings.split(input, ",")
	defer delete(str_ranges)

	buffer : [32]u8

	sum := 0
	for str_range in str_ranges {
		if len(str_range) == 0 do continue
		str_bounds, _ := strings.split(str_range, "-")
		defer delete(str_bounds)

		from, _ := strconv.parse_int(str_bounds[0])
		to, _ := strconv.parse_int(str_bounds[1])

		for i := from; i <= to; i += 1 {
			log_i := math.log(f32(i), 10)
			ceiled_log := math.ceil(log_i)
			if log_i == ceiled_log do ceiled_log += 1 // account for exact powers of 10
			length := uint(ceiled_log)

			str_i := strconv.write_uint(buffer[:], u64(i), 10)

			if length % 2 == 1 do continue
			
			if str_i[:length / 2] == str_i[length / 2:] {
				sum += i
			}
		}
	}
	return fmt.aprintf("%d", sum)
    },
    gold = proc(input: string) -> string {
	str_ranges, _ := strings.split(input, ",")
	defer delete(str_ranges)

	buffer : [32]u8

	sum := 0
	for str_range in str_ranges {
		if len(str_range) == 0 do continue
		str_bounds, _ := strings.split(str_range, "-")
		defer delete(str_bounds)

		from, _ := strconv.parse_int(str_bounds[0])
		to, _ := strconv.parse_int(str_bounds[1])

		for i := from; i <= to; i += 1 {
			log_i := math.log(f32(i), 10)
			ceiled_log := math.ceil(log_i)
			if log_i == ceiled_log do ceiled_log += 1 // account for exact powers of 10
			length := uint(ceiled_log)

			str_i := strconv.write_uint(buffer[:], u64(i), 10)

			for rep_length : uint = 1; rep_length <= length / 2; rep_length += 1 {
				if length % rep_length != 0 do continue // ignore unrepeatable pattern
				
				repeated := true
				for part :uint= 0; part < length / rep_length - 1; part += 1 {
					left := str_i[part * rep_length:(1 + part) * rep_length]
					right := str_i[(part + 1) * rep_length:(part + 2) * rep_length]
					if left != right {
						repeated = false
						break
					}
				}

				if repeated {
					sum += i
					break
				}

			}
		}
	}
	return fmt.aprintf("%d", sum)
    },
}
