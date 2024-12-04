package solution202403

import (
	"fmt"
	"regexp"
	"strconv"
	solutionRegister "vorban/advent-of-code/pkg"
)

type Operation int

const (
	Mul = iota
	Do
	Dont
)

type Instruction struct {
	op    Operation
	left  int
	right int
}

func (i Instruction) Execute() int {
	if i.op == Mul {
		return i.left * i.right
	}

	return 0
}

func parse(input string) []Instruction {
	r, _ := regexp.Compile(`(mul)\((\d+),(\d+)\)|(do)\(\)|(don't)\(\)`)
	matches := r.FindAllStringSubmatch(input, -1)

	result := []Instruction{}
	for _, match := range matches {
		switch {
		case match[1] != "":
			left, _ := strconv.Atoi(match[2])
			right, _ := strconv.Atoi(match[3])
			result = append(result, Instruction{Mul, left, right})
		case match[4] != "":
			result = append(result, Instruction{Do, 0, 0})
		case match[5] != "":
			result = append(result, Instruction{Dont, 0, 0})
		}
	}

	return result
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		result := 0

		instructions := parse(input)
		for _, instruction := range instructions {
			result += instruction.Execute()
		}

		return fmt.Sprintf("%d", result)
	},
	Gold: func(input string) string {
		result := 0

		instructions := parse(input)
		disabled := false
		for _, instruction := range instructions {
			if instruction.op == Do {
				disabled = false
			} else if instruction.op == Dont {
				disabled = true
			} else if !disabled {
				result += instruction.Execute()
			}
		}

		return fmt.Sprintf("%d", result)
	},
}
