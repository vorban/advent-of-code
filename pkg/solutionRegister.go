package solutionRegister

import (
	"fmt"
	"os"
)

/*
|==========================================================================
| MARK: Public
|==========================================================================
*/

type Solution struct {
	Silver func(input string) string
	Gold   func(input string) string
}

func Add(year string, day string, s Solution) Solution {
	solutions[getKey(year, day)] = s

	return s
}

func Run(year string, day string, part string, sample bool) string {
	key := getKey(year, day)

	s := solutions[year+day]
	input := loadInput(key, sample)

	if part == "silver" {
		return s.Silver(input)
	}

	return s.Gold(input)
}

/*
|==========================================================================
| MARK: Private
|==========================================================================
*/

var solutions = map[string]Solution{}

func loadInput(key string, sample bool) string {
	if sample {
		key += "-sample"
	}

	key += ".txt"
	key = fmt.Sprintf("assets/%s", key)

	content, err := os.ReadFile(key)
	if err != nil {
		fmt.Printf("Error reading file [%s]\n", key)
		os.Exit(-1)
	}

	return string(content)
}

func getKey(year string, day string) string {
	return year + day
}
