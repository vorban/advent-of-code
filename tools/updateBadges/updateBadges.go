package main

import (
	"fmt"
	"io"
	"log"
	"net/http"
	"os"
	"regexp"
	"strconv"
	"strings"
	"time"

	"github.com/joho/godotenv"
	"github.com/pterm/pterm"
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
}

func (b Badge) ToBadgeString() string {
	return fmt.Sprintf(
		"<img src=\"%s/%d%%20⭐-%02d-%s\">\n",
		URL, b.Year, b.Stars, b.Color())
}

func (b Badge) Color() string {
	switch {
	case b.Stars >= 50:
		return COLOR_GOLDEN
	case b.Stars > 0:
		return COLOR_SILVER
	default:
		return COLOR_GREY
	}
}

func getHtml() string {
	err := godotenv.Load()
	if err != nil {
		log.Fatal("Error loading .env file")
	}

	// ask for user data
	var username, repository, email, session string

	if username = os.Getenv("GITHUB_USERNAME"); username == "" {
		username, _ = pterm.DefaultInteractiveTextInput.Show("Enter your github.com username")
		envFile, err := os.ReadFile(".env")
		if err != nil {
			panic(err)
		}
		env, _ := godotenv.UnmarshalBytes(envFile)
		env["GITHUB_USERNAME"] = username
		_ = godotenv.Write(env, "./.env")
	}
	if repository = os.Getenv("GITHUB_REPOSITORY"); repository == "" {
		repository, _ = pterm.DefaultInteractiveTextInput.Show("Enter your github.com repository")
	}
	if email = os.Getenv("GITHUB_EMAIL"); email == "" {
		email, _ = pterm.DefaultInteractiveTextInput.Show("Enter your github.com email")
	}
	if session = os.Getenv("ADVENT_SESSION"); session == "" {
		session, _ = pterm.DefaultInteractiveTextInput.WithMask("*").Show("Enter your adventofcode.com session ID")
	}

	if session == "" {
		panic("Session ID is required")
	}
	if username == "" {
		panic("Username is required")
	}
	if repository == "" {
		panic("Repository is required")
	}
	if email == "" {
		panic("Email is required")
	}

	req, _ := http.NewRequest(http.MethodGet, "https://adventofcode.com/events", nil)
	req.Header.Set("User-Agent", fmt.Sprintf("github.com/%s/%s by %s", username, repository, email))
	req.Header.Set("Cookie", fmt.Sprintf("session=%s", session))

	res, err := http.DefaultClient.Do(req)
	if err != nil {
		panic(err)
	}

	body, err := io.ReadAll(res.Body)

	if err != nil {
		panic(err)
	}

	return string(body)
}

func getStars(html string) map[int]int {
	years := map[int]int{}

	r := regexp.MustCompile(`\[(\d{4})\]\D*([0-9]+)\*`)
	matches := r.FindAllStringSubmatch(html, -1)

	for _, match := range matches {
		fmt.Printf("Year: %s, Stars: %s\n", match[1], match[2])
	}

	return years
}

func main() {
	html := getHtml()
	stars := getStars(html)
	fmt.Printf("%v\n", stars)

	// detect the last year
	var lastYear int
	if time.Now().Month() == time.December {
		lastYear = time.Now().Year()
	} else {
		lastYear = time.Now().Year() - 1
	}

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
			badges += "<br>\n"
		}
	}

	totalStars := fmt.Sprintf(
		"<img src=\"https://img.shields.io/badge/total_stars%%20⭐-%03d-fcd34d?style=for-the-badge\">",
		sum)

	goBadge := "<img src=\"https://img.shields.io/badge/go-%2300ADD8.svg?style=for-the-badge&logo=go&logoColor=white\">"

	body := fmt.Sprintf(
		"<div>\n%s\n%s\n<br/>\n<div>\n%s\n</div>\n</div>\n%s",
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
