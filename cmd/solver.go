package main

import (
	"fmt"
	"os"
	solutions "vorban/advent-of-code/internal"
	solutionRegister "vorban/advent-of-code/pkg"
)

func main() {
	args := os.Args[1:]

	year := args[0]
	day := args[1]

	InitSolutions()

	result := solutionRegister.Run(year, day, args[2], len(args) > 3)
	fmt.Printf("Result: %s\n", result)
}

func InitSolutions() {
	solutionRegister.Add("2024", "01", solutions.Day202401)
}