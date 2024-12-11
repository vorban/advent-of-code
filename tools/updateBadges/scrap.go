package main

import (
	"fmt"
	"io"
	"log"
	"net/http"
	"os"
	"regexp"
	"strconv"
	"time"
)

/*
|--------------------------------------------------------------------------
| MARK: Public
|--------------------------------------------------------------------------
*/

const AOC_BASE_URL = "https://adventofcode.com/"

func GetStarsForYear(year int, creds Credentials) map[int]int {
	url := fmt.Sprintf("%s%d", AOC_BASE_URL, year)

	html, found := checkCache(year)

	if !found {
		time.Sleep(2 * time.Second) // limit impact on AoC servers
		html = fetchHtml(url, creds)
		os.Mkdir(fmt.Sprintf("./cache/%s", time.Now().Format("2006-01-02")), 0755)
		err := os.WriteFile(fmt.Sprintf("./cache/%s/%d.html", time.Now().Format("2006-01-02"), year), []byte(html), 0644)
		if err != nil {
			log.Fatal("Could not write cache file: " + fmt.Sprintf("./cache/%s/%d.html", time.Now().Format("2006-01-02"), year))
		}
	}
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

func checkCache(year int) (string, bool) {
	currentDate := time.Now().Format("2006-01-02")

	entries, err := os.ReadDir("./cache/" + currentDate)
	if err != nil {
		return "", false
	}

	for _, entry := range entries {
		if entry.Name() == fmt.Sprintf("%d.html", year) {
			content, err := os.ReadFile("./cache/" + currentDate + "/" + entry.Name())
			if err != nil {
				log.Fatal("Could not read cache file: " + entry.Name())
			}

			return string(content), true
		}
	}

	return "", false
}

func fetchHtml(url string, creds Credentials) string {
	req, _ := http.NewRequest(http.MethodGet, url, nil)
	req.Header.Set("User-Agent", fmt.Sprintf("github.com/%s/%s by %s", creds.Username, creds.Repository, creds.Email))
	req.Header.Set("Cookie", fmt.Sprintf("session=%s", creds.Session))

	res, err := http.DefaultClient.Do(req)
	if err != nil {
		log.Fatal("Could not fetch URL: " + url)
	}

	body, err := io.ReadAll(res.Body)

	if err != nil {
		log.Fatal("Could not read response body.")
	}

	return string(body)
}
