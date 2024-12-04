package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
	"time"
)

const MARKER = "<!-- ----- marker: badges ----- -->"
const FIRST_YEAR = 2015

const COLOR_GOLDEN = "fcd34d"
const COLOR_SILVER = "f4f4f5"
const COLOR_GREY = "a8a29e"

const URL = "https://img.shields.io/badge"

type Badge struct {
	Year  int
	Stars int
	Color string
}

func (b Badge) ToBadgeString() string {
	return fmt.Sprintf(
		"\t\t<img src=\"%s/%d%%20⭐-%02d-%s\">\n",
		URL, b.Year, b.Stars, b.Color)
}

func getColor(stars int) string {
	switch {
	case stars >= 50:
		return COLOR_GOLDEN
	case stars > 0:
		return COLOR_SILVER
	default:
		return COLOR_GREY
	}
}

func main() {
	// detect the last year
	var lastYear int
	if time.Now().Month() == time.December {
		lastYear = time.Now().Year()
	} else {
		lastYear = time.Now().Year() - 1
	}

	// prompt user for the number of stars for each year
	years := []Badge{}
	for year := FIRST_YEAR; year <= lastYear; year++ {
		var input string
		fmt.Printf("Enter the number of stars for %d: ", year)
		_, err := fmt.Scanln(&input)
		if err != nil {
			panic(err)
		}

		parsed, err := strconv.Atoi(input)
		if err != nil {
			panic(err)
		}

		years = append(years, Badge{
			Year:  year,
			Stars: parsed,
			Color: getColor(parsed),
		})
	}

	//  compute the total number of stars
	sum := 0
	for _, badge := range years {
		sum += badge.Stars
	}

	// generate the badges
	badges := ""
	for i, badge := range years {
		badges += badge.ToBadgeString()

		if (i+1)%5 == 0 {
			badges += "\t\t<br>\n"
		}
	}

	totalStars := fmt.Sprintf(
		"<img src=\"https://img.shields.io/badge/total_stars%%20⭐-%03d-fcd34d?style=for-the-badge\">",
		sum)

	goBadge := "<img src=\"https://img.shields.io/badge/go-%2300ADD8.svg?style=for-the-badge&logo=go&logoColor=white\">"

	body := fmt.Sprintf(
		"<div>\n\t%s\n\t%s\n<br/>\n\t<div>\n%s\n\t</div>\n</div>\n%s\n",
		goBadge, totalStars, badges, MARKER)

	f, err := os.ReadFile("./README.md")
	if err != nil {
		panic(err)
	}

	_, after, found := strings.Cut(string(f), MARKER)
	if !found {
		panic("Marker not found")
	}

	content := body + after

	os.WriteFile("./README.md", []byte(content), 0644)
}
