package solution202405

import (
	"fmt"
	"slices"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

func parseRules(input string) map[int][]int {
	parts := strings.Split(input, "\n\n")

	rules := map[int][]int{}
	lines := strings.Split(parts[0], "\n")
	for _, line := range lines {
		if line == "" {
			continue
		}

		splitted := strings.Split(line, "|")
		page, _ := strconv.Atoi(splitted[0])
		before, _ := strconv.Atoi(splitted[1])
		if _, ok := rules[page]; !ok {
			rules[page] = []int{}
		}
		index := slices.Index(rules[page], before)
		if index == -1 {
			rules[page] = append(rules[page], before)
		}
	}

	return rules
}

func parseUpdates(input string) [][]int {
	parts := strings.Split(input, "\n\n")

	updates := [][]int{}
	lines := strings.Split(parts[1], "\n")
	for _, line := range lines {
		if line == "" {
			continue
		}
		list := []int{}
		for _, number := range strings.Split(line, ",") {
			num, _ := strconv.Atoi(number)
			list = append(list, num)
		}
		updates = append(updates, list)
	}

	return updates
}

func isValid(rules map[int][]int, update []int) bool {
	for i, page := range update {
		// if no rules for page, skip
		if _, ok := rules[page]; !ok {
			continue
		}
		// for each rule, check if all `before` pages
		// are in update and before `page`
		for _, before := range rules[page] {
			beforeIndex := slices.Index(update, before)
			if beforeIndex != -1 && beforeIndex <= i {
				return false
			}
		}
	}

	return true
}

func fix(rules map[int][]int, update []int) []int {
	changed := true
	for changed {
		changed = false
		for i, page := range update {
			// if no rules for page, skip
			if _, ok := rules[page]; !ok {
				continue
			}
			// for each rule, check if all `before` pages
			// are in update and before `page`
			for _, before := range rules[page] {
				beforeIndex := slices.Index(update, before)
				if beforeIndex != -1 && beforeIndex <= i {
					update[beforeIndex], update[i] = update[i], update[beforeIndex]
					changed = true
				}
			}
		}
	}

	return update
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		rules := parseRules(input)
		updates := parseUpdates(input)
		valid := [][]int{}

		for _, update := range updates {
			if !isValid(rules, update) {
				continue
			}
			valid = append(valid, update)
		}

		result := 0
		for _, update := range valid {
			middle := len(update) / 2
			result += update[middle]
		}

		return fmt.Sprintf("%d", result)
	},
	Gold: func(input string) string {
		rules := parseRules(input)
		updates := parseUpdates(input)
		invalid := [][]int{}

		for _, update := range updates {
			if isValid(rules, update) {
				continue
			}
			invalid = append(invalid, update)
		}

		result := 0
		for _, update := range invalid {
			update = fix(rules, update)

			middle := len(update) / 2
			result += update[middle]
		}

		return fmt.Sprintf("%d", result)
	},
}
