package solution202406

import (
	"fmt"
	"slices"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

type ObjectType int

const (
	Floor ObjectType = iota
	Obstacle
	Guard
)

type Object struct {
	t ObjectType
	x int
	y int
}

func charToObjectType(char string) ObjectType {
	switch char {
	case ".":
		return Floor
	case "#":
		return Obstacle
	case "^", "v", "<", ">":
		return Guard
	default:
		panic(fmt.Sprintf("unexpected character: %s", char))
	}
}

func charToDirection(char string) (dx int, dy int) {
	switch char {
	case "^":
		return 0, -1
	case "v":
		return 0, 1
	case "<":
		return -1, 0
	case ">":
		return 1, 0
	default:
		panic(fmt.Sprintf("unexpected character: %s", char))
	}
}

func parse(input string) (objects []Object, width int, height int) {
	lines := strings.Split(strings.TrimSpace(input), "\n")
	height = len(lines)

	for y, line := range lines {
		chars := strings.Split(strings.TrimSpace(line), "")
		width = len(chars)
		for x, char := range chars {
			t := charToObjectType(char)
			if t == Floor {
				continue
			}
			objects = append(objects, Object{t, x, y})
		}
	}

	return
}

func objectToMapKey(o Object) string {
	return fmt.Sprintf("%d,%d", o.x, o.y)
}

func mapKeyToPosition(key string) (x int, y int) {
	coords := strings.Split(key, ",")
	x, _ = strconv.Atoi(coords[0])
	y, _ = strconv.Atoi(coords[1])
	return
}

func GetVisitedPositions(objects []Object, guard Object, dx, dy, width, height int) (map[string]string, bool) {
	// run the simulation
	visited := make(map[string]string)
	looped := false
	for guard.x >= 0 && guard.x < width && guard.y >= 0 && guard.y < height {
		key := objectToMapKey(guard)
		if value, ok := visited[key]; !ok {
			visited[key] = objectToMapKey(Object{Floor, dx, dy})
		} else {
			oldDx, oldDy := mapKeyToPosition(value)
			if oldDx == dx && oldDy == dy {
				looped = true
				break
			}
		}

		// check if we need to turn
		for {
			nextX, nextY := guard.x+dx, guard.y+dy
			hasObstacle := slices.IndexFunc(objects, func(o Object) bool {
				return o.x == nextX && o.y == nextY
			}) != -1

			if !hasObstacle {
				break
			}

			// turn right
			dx, dy = -dy, dx
		}

		// move the guard
		guard.x += dx
		guard.y += dy
	}

	return visited, looped
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		// get objects and map dimensions
		objects, width, height := parse(input)

		// extract the guard from objects
		guardIndex := slices.IndexFunc(objects, func(o Object) bool {
			return o.t == Guard
		})
		guard := objects[guardIndex]
		objects = append(objects[:guardIndex], objects[guardIndex+1:]...)

		// figure out this inital facing
		posInInput := strings.IndexAny(input, "^v<>")
		dx, dy := charToDirection(string(input[posInInput]))

		visited, _ := GetVisitedPositions(objects, guard, dx, dy, width, height)

		return fmt.Sprintf("%d", len(visited))
	},
	Gold: func(input string) string {
		// get objects and map dimensions
		objects, width, height := parse(input)

		// extract the guard from objects
		guardIndex := slices.IndexFunc(objects, func(o Object) bool {
			return o.t == Guard
		})
		guard := objects[guardIndex]
		objects = append(objects[:guardIndex], objects[guardIndex+1:]...)

		// figure out this inital facing
		posInInput := strings.IndexAny(input, "^v<>")
		dx, dy := charToDirection(string(input[posInInput]))

		// get the visited points, because it's useless to add an obstacle to an ignored point
		visited, _ := GetVisitedPositions(objects, guard, dx, dy, width, height)

		loopedCount := 0
		for key := range visited {
			x, y := mapKeyToPosition(key)
			objects = append(objects, Object{Obstacle, x, y})

			_, looped := GetVisitedPositions(objects, guard, dx, dy, width, height)
			if looped {
				loopedCount++
			}

			// remove the last added obstacle
			objects = objects[:len(objects)-1]
		}
		return fmt.Sprintf("%d", loopedCount)
	},
}
