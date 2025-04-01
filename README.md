# Prerequisites

## Make sure you run composer install to setup the project and install phpunit

`composer install`

## PHP version

Tetsted on 8.3.6 and should run seamlessly on php 8.4

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


# Task 2 - Word Frequency Counter

## Assumptions
- A file system storage is used for saving the state of the user feeding with a data.
- The maximum POST request size is not set, e.g. it depends on the ini value - post_max_size
- The system uses the built in php development server
- The system is not intended to handle large input data (along with the post_max_size limit), because it uses json file for persistring the word count. To handle larger data I will recommend using nosql solution for storing and accessing fast. NoSQL solutions like MongoDB or Redis supports availability and scalability (depends if we needed it) through sharding for example.
- The tests are not covering the caching functionality

## The Architecture

The architecture is simple:

```
task2.php
```
 This is a document root - the entrypoint which the built in server will use

```
src/Controllers/WordFrequencyController.php
``` 
This is the controller for handling POST and GET requests
```
src/Services/WordFrequencyService.php
``` 
Is the main service for feeding the system with a text and getting the word frequencies

```
word_frequencies.json
```
This is the file storage

```

```
src/Services/Cache/DummyCache.php
```
A dummy cache to show the possibility of Cache-Aside strategy. A potential solution would be in-memory cache as Redis.