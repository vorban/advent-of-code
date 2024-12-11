package main

import (
	"fmt"
	"os"
	"slices"
	"strings"
	"time"

	"github.com/pterm/pterm"
)

const FIRST_YEAR = 2015

func main() {
	// detect the last year
	var lastYear int
	if time.Now().Month() == time.December {
		lastYear = time.Now().Year()
	} else {
		lastYear = time.Now().Year() - 1
	}

	solvedDays := GetSolvedDays()

	years := []Badge{}
	creds := GetCredentials()
	p, _ := pterm.DefaultProgressbar.WithTotal(lastYear - FIRST_YEAR + 1).WithTitle("Updating: XXXX").Start()
	for year := FIRST_YEAR; year <= lastYear; year++ {
		p.UpdateTitle(fmt.Sprintf("Updating: %d", year))
		time.Sleep(1 * time.Second)
		stars := GetStarsForYear(year, creds)
		actualStars := 0
		for day := 1; day <= 25; day++ {
			key := fmt.Sprintf("%d-%02d", year, day)
			if stars[day] > 0 && slices.Contains(solvedDays, key) {
				actualStars += stars[day]
			}
		}
		years = append(years, Badge{year, actualStars})
		pterm.Success.Println("Updated " + fmt.Sprintf("%d", year))
		p.Increment()
	}

	f, err := os.ReadFile("./README.md")
	if err != nil {
		panic(err)
	}

	_, after, found := strings.Cut(string(f), MARKER)
	if !found {
		panic("Marker not found")
	}

	content := generateReadmeSection(years) + after

	os.WriteFile("./README.md", []byte(content), 0644)
}
