# Prerequisites

Make sure you run composer install to setup the project and install phpunit

`composer install`

# Task 1 - Advertising bid auction

## Run the script:

Here are a few examples with different scenarios:

1. Standard example provided in the task:
   ```
   php task1.php data/example.csv
   ```

2. File with duplicate high bids:
   ```
   php task1.php data/duplicate_high.csv
   ```

3. File with only one bid:
   ```
   php task1.php data/one_bid.csv
   ```

4. Empty file (edge case):
   ```
   php task1.php data/empty.csv
   ```

5. Invalid format test:
   ```
   php task1.php data/invalid_format.csv
   ```

## Running tests for Task 1

```
./vendor/bin/phpunit tests/BidProcessorTest.php
```