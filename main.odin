package main

import "./solutions"
import "./utils"
import "core:flags"
import "core:fmt"
import "core:os"
import "core:strconv"

Command_Line :: struct {
	year:   string `args:"pos=0" usage:"Year."`,
	day:    string `args:"pos=1" usage:"Day."`,
	silver: bool `usage:"Run silver versions."`,
	gold:   bool `usage:"Run gold versions."`,
}

main :: proc() {
	solvers := register_solvers()
	defer delete(solvers)

	cli: Command_Line
	flags.parse_or_exit(&cli, os.args)

	should_run_all_days := cli.day == ""
	should_run_all_years := cli.year == ""

	first_year := utils.get_first_year()
	last_year := utils.get_last_year()
	if !should_run_all_years {
		first_year, _ = strconv.parse_int(cli.year)
		last_year = first_year
	}

	for y := first_year; y <= last_year; y += 1 {
		fmt.printf("===== YEAR %d =====\n", y)

		first_day := 1
		last_day := utils.get_last_day(y)
		if !should_run_all_days {
			first_day, _ = strconv.parse_int(cli.day)
			last_day = first_day
		}

		for d := first_day; d <= last_day; d += 1 {
			key := fmt.aprintf("%04d_%02d", y, d)
			defer delete(key)

			solver, ok := solvers[key]
			if !ok do continue

			input := utils.get_input_file(key)
			if input == "" do continue


			fmt.printf("DAY %d:\n", d)

			// either silver flag passed or no flags passed meaning execute all
			if cli.silver || !cli.gold {
				s, t := utils.run_timed_solver(solver.silver, input)
				fmt.printf("- Silver [% 4dms]: %s\n", t, s)
				delete(s)
			}

			if cli.gold || !cli.silver {
				s, t := utils.run_timed_solver(solver.gold, input)
				fmt.printf("- Gold   [% 4dms]: %s\n", t, s)
				delete(s)
			}
		}
	}
}

register_solvers :: proc() -> map[string]utils.Solver {
	solvers := make(map[string]utils.Solver)

	solvers["2015_01"] = solutions.SOLVER_2015_01
	solvers["2015_02"] = solutions.SOLVER_2015_02

	return solvers
}
