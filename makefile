# Usage: make run YEAR=2015 DAY=01

YEAR ?= 2015
DAY  ?= 01

PUZZLE = solutions/solver_$(YEAR)_$(DAY).odin
STUB = day.stub

init:
	@echo Creating new puzzle file for year $(YEAR), day $(DAY)
	@mkdir -p puzzles
	@sed -e "s/YYYY/$(YEAR)/g" -e "s/DD/$(DAY)/g" $(STUB) > $(PUZZLE)
	@echo Created $(PUZZLE)

clean:
	@echo "Removing all .exe files in current directory..."
	-rm -f *.exe
