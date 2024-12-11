package main

import (
	"os"
	"regexp"
)

/*
|--------------------------------------------------------------------------
| MARK: Public
|--------------------------------------------------------------------------
*/

func GetSolvedDays() []string {
	entries, _ := os.ReadDir("./internal")

	solved := []string{}
	for _, entry := range entries {
		if !entry.IsDir() {
			continue
		}

		r := regexp.MustCompile(`(\d{4})-(\d{2})`)
		if !r.MatchString(entry.Name()) {
			continue
		}

		solved = append(solved, entry.Name())
	}

	return solved
}
