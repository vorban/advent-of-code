package main

import (
	"fmt"
	"os"
	"strconv"
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
	solution202408 "vorban/advent-of-code/internal/2024-08"
	solution202409 "vorban/advent-of-code/internal/2024-09"
	solution202410 "vorban/advent-of-code/internal/2024-10"
	solution202411 "vorban/advent-of-code/internal/2024-11"
	solution202412 "vorban/advent-of-code/internal/2024-12"
	solution202413 "vorban/advent-of-code/internal/2024-13"
	solution202414 "vorban/advent-of-code/internal/2024-14"
	// ----- marker: discovery - imports ----- //
)

func main() {
	args := os.Args[1:]

	year := args[0] // ex: 2024
	day := args[1]  // ex: 02
	part := args[2] // ex: silver

	sampled := len(args) > 3 // ex: solver 2024 02 silver |sample|

	sample := 1 // ex: solver 2024 02 silver |sample| 2
	if len(args) > 4 {
		sample, _ = strconv.Atoi(args[4])
	}

	DiscoverSolutions()

	result := solutionRegister.Run(year, day, part, sampled, sample)
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
	solutionRegister.Add("2024", "08", solution202408.Solution)
	solutionRegister.Add("2024", "09", solution202409.Solution)
	solutionRegister.Add("2024", "10", solution202410.Solution)
	solutionRegister.Add("2024", "11", solution202411.Solution)
	solutionRegister.Add("2024", "12", solution202412.Solution)
	solutionRegister.Add("2024", "13", solution202413.Solution)
	solutionRegister.Add("2024", "14", solution202414.Solution)
}
