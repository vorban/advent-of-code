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
	args := os.Args[1:]

	if len(args) > 0 {
		year := args[0]
		day := args[1]
		initDay(year, day)
	}

	updateSolver()
}

func initDay(year, day string) {
	// create the directory
	dir := fmt.Sprintf("./internal/%s-%s", year, day)
	err := os.Mkdir(dir, 0755)
	if err != nil {
		panic(err)
	}

	// read stub file
	stub, err := os.ReadFile("./assets/solution.stub")
	if err != nil {
		panic(err)
	}

	// replace {{ package }} with the correct package name
	stub = []byte(strings.ReplaceAll(string(stub), "{{ package }}", fmt.Sprintf("solution%s%s", year, day)))

	// create the main.go file
	f, err := os.Create(fmt.Sprintf("%s/main.go", dir))
	if err != nil {
		panic(err)
	}
	defer f.Close()

	f.Write(stub)

	// create the sample file and the input file
	err = os.WriteFile(fmt.Sprintf("./assets/%s%s-sample.txt", year, day), []byte(""), 0644)
	if err != nil {
		panic(err)
	}
	err = os.WriteFile(fmt.Sprintf("./assets/%s%s.txt", year, day), []byte(""), 0644)
	if err != nil {
		panic(err)
	}
}

func updateSolver() {
	// get files in ./internal
	files, err := os.ReadDir("./internal")
	if err != nil {
		panic(err)
	}

	entries := []Entry{}
	for _, file := range files {
		r, _ := regexp.Compile(`(\d{4})-(\d{2})`)
		parts := r.FindStringSubmatch(file.Name())

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
