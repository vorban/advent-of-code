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
