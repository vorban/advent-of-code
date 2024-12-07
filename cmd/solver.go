package main

import (
	"fmt"
	"os"
	solutionRegister "vorban/advent-of-code/pkg"

	// ----- marker: discovery - imports ----- //
	solution202101 "vorban/advent-of-code/internal/2021-01"
	solution202102 "vorban/advent-of-code/internal/2021-02"
	solution202103 "vorban/advent-of-code/internal/2021-03"
	solution202401 "vorban/advent-of-code/internal/2024-01"
	solution202402 "vorban/advent-of-code/internal/2024-02"
	solution202403 "vorban/advent-of-code/internal/2024-03"
	solution202404 "vorban/advent-of-code/internal/2024-04"
	solution202405 "vorban/advent-of-code/internal/2024-05"
	solution202406 "vorban/advent-of-code/internal/2024-06"
	solution202407 "vorban/advent-of-code/internal/2024-07"
	// ----- marker: discovery - imports ----- //
)

func main() {
	args := os.Args[1:]

	year := args[0]
	day := args[1]

	DiscoverSolutions()

	result := solutionRegister.Run(year, day, args[2], len(args) > 3)
	fmt.Printf("Result: %s\n", result)
}

// ----- marker: discovery ----- //
func DiscoverSolutions() {
	solutionRegister.Add("2021", "01", solution202101.Solution)
	solutionRegister.Add("2021", "02", solution202102.Solution)
	solutionRegister.Add("2021", "03", solution202103.Solution)
	solutionRegister.Add("2024", "01", solution202401.Solution)
	solutionRegister.Add("2024", "02", solution202402.Solution)
	solutionRegister.Add("2024", "03", solution202403.Solution)
	solutionRegister.Add("2024", "04", solution202404.Solution)
	solutionRegister.Add("2024", "05", solution202405.Solution)
	solutionRegister.Add("2024", "06", solution202406.Solution)
	solutionRegister.Add("2024", "07", solution202407.Solution)
}
