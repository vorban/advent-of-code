package solution202409

import (
	"fmt"
	"slices"
	"strconv"
	"strings"
	solutionRegister "vorban/advent-of-code/pkg"
)

type Block struct {
	id   int
	size int
}

func parse(input string) []int {
	filesystem := []int{}

	id := 0
	isFile := false // the isFile toggle happens before parsing, so the first one will be a file
	for _, char := range strings.Split(input, "") {
		if char == "" {
			continue
		}

		isFile = !isFile
		blocks, _ := strconv.Atoi(char)

		if blocks == 0 {
			continue
		}

		if isFile {
			for i := 0; i < blocks; i++ {
				filesystem = append(filesystem, id)
			}
			id++
		} else {
			for i := 0; i < blocks; i++ {
				filesystem = append(filesystem, -1)
			}
		}
	}
	return filesystem
}

func intoBlocks(rawFilesystem []int) []Block {
	id := rawFilesystem[0]
	filesystem := []Block{
		{id: id, size: 1},
	}
	fsPointer := 0

	for i := 1; i < len(rawFilesystem); i++ {
		if rawFilesystem[i] == id {
			filesystem[fsPointer].size++
			continue
		}
		id = rawFilesystem[i]
		filesystem = append(filesystem, Block{id: id, size: 1})
		fsPointer++
	}

	return filesystem
}

func updateStartIndex(filesystem []int, startIndex int) int {
	for startIndex < len(filesystem) && filesystem[startIndex] != -1 {
		startIndex++
	}
	return startIndex
}

func updateEndIndex(filesystem []int, endIndex int) int {
	for endIndex >= 0 && filesystem[endIndex] == -1 {
		endIndex--
	}
	return endIndex
}

func updateStartBlock(filesystem []Block, startBlock int) int {
	for startBlock < len(filesystem) && filesystem[startBlock].id != -1 {
		startBlock++
	}
	return startBlock
}

func updateEndBlock(filesystem []Block, endBlock int) int {
	for endBlock >= 0 && filesystem[endBlock].id == -1 {
		endBlock--
	}
	return endBlock
}

func checksum(filesystem []int) int {
	checksum := 0
	for i, id := range filesystem {
		if id == -1 {
			break
		}
		checksum += i * id
	}
	return checksum
}

func blockChecksum(filesystem []Block) int {
	checksum := 0

	i := 0
	for _, block := range filesystem {
		if block.id == -1 {
			i += block.size
			continue
		}
		for j := 0; j < block.size; j++ {
			fmt.Printf("Checksum = %d + %d * %d\n", checksum, i, block.id)
			checksum += i * block.id
			i++
		}
	}
	return checksum
}

var Solution = solutionRegister.Solution{
	Silver: func(input string) string {
		filesystem := parse(input)

		startIndex := updateStartIndex(filesystem, 0)
		endIndex := updateEndIndex(filesystem, len(filesystem)-1)
		for startIndex <= endIndex {
			filesystem[startIndex], filesystem[endIndex] = filesystem[endIndex], filesystem[startIndex]

			startIndex = updateStartIndex(filesystem, startIndex)
			endIndex = updateEndIndex(filesystem, endIndex)
		}

		return fmt.Sprintf("%d", checksum(filesystem))
	},
	Gold: func(input string) string {
		rawFilesystem := parse(input)
		filesystem := intoBlocks(rawFilesystem)

		endBlock := updateEndBlock(filesystem, len(filesystem)-1)
		for endBlock >= 0 {
			startBlock := updateStartBlock(filesystem, 0)
			hasShifted := false
			for startBlock < endBlock {
				if filesystem[startBlock].size < filesystem[endBlock].size {
					startBlock = updateStartBlock(filesystem, startBlock+1)
					continue
				}

				filesystem[startBlock].size -= filesystem[endBlock].size
				endBlockTmp := filesystem[endBlock]
				filesystem[endBlock].id = -1
				filesystem = slices.Insert(filesystem, startBlock, endBlockTmp)
				hasShifted = true
				break
			}
			if hasShifted {
				endBlock = updateEndBlock(filesystem, endBlock)
			} else {
				endBlock = updateEndBlock(filesystem, endBlock-1)
			}
		}

		return fmt.Sprintf("%d", blockChecksum(filesystem))
	},
}
