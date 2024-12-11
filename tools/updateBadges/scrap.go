package main

import (
	"fmt"
	"io"
	"net/http"
	"regexp"
	"strconv"
)

/*
|--------------------------------------------------------------------------
| MARK: Public
|--------------------------------------------------------------------------
*/

const AOC_BASE_URL = "https://adventofcode.com/"

func GetStarsForYear(year int, creds Credentials) map[int]int {
	url := fmt.Sprintf("%s%d", AOC_BASE_URL, year)

	html := fetchHtml(url, creds)
	days := map[int]int{}

	r := regexp.MustCompile(`aria-label="Day (\d{1,2}), (one|two) stars"`)
	matches := r.FindAllStringSubmatch(html, -1)

	for _, match := range matches {
		day, _ := strconv.Atoi(match[1])
		var stars int
		if match[2] == "one" {
			stars = 1
		} else {
			stars = 2
		}
		days[day] = stars
	}

	return days
}

/*
|--------------------------------------------------------------------------
| MARK: Private
|--------------------------------------------------------------------------
*/

func fetchHtml(url string, creds Credentials) string {
	req, _ := http.NewRequest(http.MethodGet, url, nil)
	req.Header.Set("User-Agent", fmt.Sprintf("github.com/%s/%s by %s", creds.Username, creds.Repository, creds.Email))
	req.Header.Set("Cookie", fmt.Sprintf("session=%s", creds.Session))

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
