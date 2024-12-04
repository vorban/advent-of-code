package main

import (
	"fmt"
	"os"
	"regexp"
	"strings"
)

type Entry struct {
	year string
	day  string
}

const MARKER = "// ----- marker: discovery ----- //"

func main() {
	// get files in ./internal
	files, err := os.ReadDir("./internal")
	if err != nil {
		panic(err)
	}

	entries := []Entry{}
	for _, file := range files {
		r, _ := regexp.Compile(`(\d{4})-(\d{2})\.go`)
		parts := r.FindStringSubmatch(file.Name())

		// skip directories and unmatched files
		if file.IsDir() || len(parts) <= 1 {
			continue
		}

		// extract year and day from the filename
		entries = append(entries, Entry{
			year: parts[1],
			day:  parts[2],
		})
	}

	// generate the DiscoverSolutions function
	f, err := os.ReadFile("./cmd/solver.go")
	if err != nil {
		panic(err)
	}

	content, _, found := strings.Cut(string(f), MARKER)
	if !found {
		panic("Marker not found")
	}

	// generate the function body
	body := MARKER + "\nfunc DiscoverSolutions() {\n"
	for _, entry := range entries {
		body += fmt.Sprintf("\tsolutionRegister.Add(\"%s\", \"%s\", solutions.Day%s%s)\n", entry.year, entry.day, entry.year, entry.day)
	}
	body += "}\n"

	// write the new content
	os.WriteFile("./cmd/solver.go", []byte(content+body), 0644)
}
