package main

import "fmt"

type Badge struct {
	Year  int
	Stars int
}

const BASE_IMGSHIELD_URL = "https://img.shields.io/badge/"
const MARKER = "<!-- ----- marker: badges ----- -->"

const COLOR_GOLDEN = "fcd34d"
const COLOR_SILVER = "f4f4f5"
const COLOR_GREY = "a8a29e"

func (b Badge) ToBadgeString() string {
	return fmt.Sprintf(
		"<img src=\"%s%d%%20⭐-%02d-%s\">\n",
		BASE_IMGSHIELD_URL, b.Year, b.Stars, b.Color())
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

func GetGoBadge() string {
	return fmt.Sprintf(
		"<img src=\"%sgo-%%2300ADD8.svg?style=for-the-badge&logo=go&logoColor=white\">",
		BASE_IMGSHIELD_URL)
}

func GetTotalStarsBadge(stars int) string {
	return fmt.Sprintf(
		"<img src=\"%stotal_stars%%20⭐-%03d-fcd34d?style=for-the-badge\">",
		BASE_IMGSHIELD_URL, stars)
}

func generateReadmeSection(badges []Badge) string {
	//  compute the total number of stars
	sum := 0
	result := ""
	for i, badge := range badges {
		sum += badge.Stars
		result += badge.ToBadgeString()

		if (i+1)%5 == 0 {
			result += "<br>\n"
		}
	}

	result = fmt.Sprintf(
		"<div>\n%s\n%s\n<br/>\n<div>\n%s\n</div>\n</div>\n%s",
		GetGoBadge(), GetTotalStarsBadge(sum), result, MARKER)

	return result
}
