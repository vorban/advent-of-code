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
const IMPORT_MARKER = "// ----- marker: discovery - imports ----- //"

func main() {
	// get files in ./internal
	files, err := os.ReadDir("./internal")
	if err != nil {
		panic(err)
	}

	entries := []Entry{}
	for _, file := range files {
		r, _ := regexp.Compile(`(\d{4})-(\d{2})`)
		parts := r.FindStringSubmatch(file.Name())

		fmt.Printf("parts: %v\n", parts)

		// skip direct files and unmatched directories
		if !file.IsDir() || len(parts) != 3 {
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
		body += fmt.Sprintf(
			"\tsolutionRegister.Add(\"%s\", \"%s\", solution%s%s.Solution)\n",
			entry.year, entry.day, entry.year, entry.day)
	}
	body += "}\n"

	f = []byte(content + body)

	// generate the import statements
	parts := strings.Split(string(f), IMPORT_MARKER)
	if len(parts) != 3 {
		panic("Import marker not found")
	}

	body = IMPORT_MARKER + "\n"
	for _, entry := range entries {
		body += fmt.Sprintf(
			"\tsolution%s%s \"vorban/advent-of-code/internal/%s-%s\"\n",
			entry.year, entry.day, entry.year, entry.day)
	}
	body += "\t" + IMPORT_MARKER

	f = []byte(parts[0] + body + parts[2])

	// write the new content to solver.go
	os.WriteFile("./cmd/solver.go", f, 0644)
}
