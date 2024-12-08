package solution202408

import (
	"fmt"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

type Node struct {
	char string
	x    int
	y    int
}

func parse(input string) map[string]Node {
	nodes := map[string]Node{}

	for y, line := range strings.Split(input, "\n") {
		if line == "" {
			continue
		}

		for x, char := range strings.Split(line, "") {
			if char == "." || char == "" {
				continue
			}
			node := Node{char, x, y}
			nodes[getKey(node)] = node
		}
	}
	return nodes
}

func getLengthOfInput(input string) (int, int) {
	lines := strings.Split(input, "\n")
	return len(lines[0]), len(lines) - 1
}

func getAntinodes(node Node, cmp Node) (Node, Node) {
	dx := (cmp.x - node.x)
	dy := (cmp.y - node.y)

	one := Node{node.char, node.x - dx, node.y - dy}
	two := Node{cmp.char, cmp.x + dx, cmp.y + dy}

	return one, two
}

func getKey(node Node) string {
	return fmt.Sprintf("%d-%d", node.x, node.y)
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		width, height := getLengthOfInput(input)
		nodes := parse(input)

		antinodes := map[string]Node{}
		for _, node := range nodes {
			for _, cmp := range nodes {
				if (node.x == cmp.x && node.y == cmp.y) || node.char != cmp.char {
					continue
				}
				one, two := getAntinodes(node, cmp)
				if one.x >= 0 && one.y >= 0 && one.x < width && one.y < height {
					antinodes[getKey(one)] = one
				}
				if two.x >= 0 && two.y >= 0 && two.x < width && two.y < height {
					antinodes[getKey(two)] = two
				}
			}
		}

		return fmt.Sprintf("%d", len(antinodes))
	},
	Gold: func(input string) string {
		width, height := getLengthOfInput(input)
		nodes := parse(input)

		antinodes := map[string]Node{}
		for _, node := range nodes {
			for _, cmp := range nodes {
				if (node.x == cmp.x && node.y == cmp.y) || node.char != cmp.char {
					continue
				}
				antinodes[getKey(node)] = node
				antinodes[getKey(cmp)] = cmp

				one, two := getAntinodes(node, cmp)
				prevOne, prevTwo := cmp, cmp

				for one.x >= 0 && one.y >= 0 && one.x < width && one.y < height {
					antinodes[getKey(one)] = one
					tmp, _ := getAntinodes(one, prevOne)
					one, prevOne = tmp, one
				}
				for two.x >= 0 && two.y >= 0 && two.x < width && two.y < height {
					antinodes[getKey(two)] = two
					tmp, _ := getAntinodes(two, prevTwo)
					two, prevTwo = tmp, two
				}
			}
		}

		return fmt.Sprintf("%d", len(antinodes))
	},
}
