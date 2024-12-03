# Advent of Code 2024 

Based on Laravel to get some basic framework setup.
The idea is to work TDD in solving the puzzles (which I think is how the puzzles are meant to be solved anyway).

Use `artisan make:day n` to create classes for a new day. This will create:
- input file for the puzzle input
- Day class for the solving code
- Test class with methods to provide the puzzle example and expected outcomes for puzzle 1 and 2.

After creating, we can run tests and see them fail, for example `artisan test --filter Day1` to run the Day 1 test.

After writing code and getting to a green test, we can run the code on the actual input file: `artisan run:day n p`
where `n` is the day number and `p` is 1 or 2 to run only puzzle 1 or 2. Leave it out to run both.
