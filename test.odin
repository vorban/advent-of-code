package main

import "./solutions"
import "core:testing"

Pair :: struct {
	input:    string,
	solution: string,
}

@(test)
test_2015_01_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{input = "(())", solution = "0"},
		Pair{input = "()()", solution = "0"},
		Pair{input = "(((", solution = "3"},
		Pair{input = "(()(()(", solution = "3"},
		Pair{input = "())", solution = "-1"},
		Pair{input = "))(", solution = "-1"},
		Pair{input = ")))", solution = "-3"},
		Pair{input = ")())())", solution = "-3"},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_01.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_01_gold :: proc(t: ^testing.T) {
	pairs := []Pair{Pair{input = ")", solution = "1"}, Pair{input = "()())", solution = "5"}}

	for p in pairs {
		s := solutions.SOLVER_2015_01.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_02_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{input = "2x3x4", solution = "58"},
		Pair{input = "1x1x10", solution = "43"},
		Pair{input = "2x3x4\n1x1x10", solution = "101"},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_02.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_02_gold :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{input = "2x3x4", solution = "34"},
		Pair{input = "1x1x10", solution = "14"},
		Pair{input = "2x3x4\n1x1x10", solution = "48"},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_02.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_03_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{input = ">", solution = "2"},
		Pair{input = "^>v<", solution = "4"},
		Pair{input = "^v^v^v^v^v", solution = "2"},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_03.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_03_gold :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{input = "^v", solution = "3"},
		Pair{input = "^>v<", solution = "3"},
		Pair{input = "^v^v^v^v^v", solution = "11"},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_03.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_04_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{input = "abcdef", solution = "609043"},
		Pair{input = "pqrstuv", solution = "1048970"},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_04.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_05_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair {
			input = "ugknbfddgicrmopn\naaa\njchzalrnumimnmhp\nhaegwjzuvuyypxyu\ndvszwmarrgswjxmb\n",
			solution = "2",
		},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_05.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2015_05_gold :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair {
			input = "qjhvhtzxzqqjkmpb\nxxyxx\nuurcxstgmygtbstg\nieodomkazucvgmuy\n",
			solution = "2",
		},
	}

	for p in pairs {
		s := solutions.SOLVER_2015_05.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_01_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{input = "L68\nL30\nR48\nL5\nR60\nL55\nL1\nL99\nR14\nL82", solution = "3"}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_01.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_01_gold :: proc(t: ^testing.T) {
	pairs := []Pair{
		Pair{input = "L68\nL30\nR48\nL5\nR60\nL55\nL1\nL99\nR14\nL82", solution = "6"}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_01.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_02_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{
			input = "11-22,95-115,998-1012,1188511880-1188511890,222220-222224,1698522-169852,446443-446449,38593856-38593862,565653-565659,824824821-824824827,2121212118-2121212124",
			solution = "1227775554"
		}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_02.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_02_gold :: proc(t: ^testing.T) {
	pairs := []Pair{
		Pair{
			input = "11-22,95-115,998-1012,1188511880-1188511890,222220-222224,1698522-169852,446443-446449,38593856-38593862,565653-565659,824824821-824824827,2121212118-2121212124",
			solution = "4174379265"
		}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_02.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_03_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{
			input = "987654321111111\n811111111111119\n234234234234278\n818181911112111",
			solution = "357"
		}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_03.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_03_gold :: proc(t: ^testing.T) {
	pairs := []Pair{
		Pair{
			input = "987654321111111\n811111111111119\n234234234234278\n818181911112111",
			solution = "3121910778619"
		}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_03.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_04_silver :: proc(t: ^testing.T) {
	pairs := []Pair {
		Pair{
			input = "..@@.@@@@.\n@@@.@.@.@@\n@@@@@.@.@@\n@.@@@@..@.\n@@.@@@@.@@\n.@@@@@@@.@\n.@.@.@.@@@\n@.@@@.@@@@\n.@@@@@@@@.\n@.@.@@@.@.",
			solution = "13"
		}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_04.silver(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}

@(test)
test_2025_04_gold :: proc(t: ^testing.T) {
	pairs := []Pair{
		Pair{
			input = "..@@.@@@@.\n@@@.@.@.@@\n@@@@@.@.@@\n@.@@@@..@.\n@@.@@@@.@@\n.@@@@@@@.@\n.@.@.@.@@@\n@.@@@.@@@@\n.@@@@@@@@.\n@.@.@@@.@.",
			solution = "43"
		}
	}

	for p in pairs {
		s := solutions.SOLVER_2025_04.gold(p.input)
		defer delete(s)
		testing.expect_value(t, s, p.solution)
	}
}
