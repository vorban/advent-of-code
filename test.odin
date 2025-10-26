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
