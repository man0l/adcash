<?php

declare(strict_types=1);

namespace App;

class BidProcessor
{
    /**
     * Processes a CSV file containing ad bids to find the winning ad ID and the second-highest bid.
     *
     * @param string $filePath Path to the input CSV file.
     * @return array An array containing 'adId' (int) and 'secondBid' (float), or throws an exception on error.
     * @throws \InvalidArgumentException If the file is invalid or cannot be processed.
     * @throws \RuntimeException If no valid bids are found or only one bid is found.
     */
    public function findWinningBid(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("Error: File not found at path '{$filePath}'.");
        }

        if (!is_readable($filePath)) {
            throw new \InvalidArgumentException("Error: File is not readable at path '{$filePath}'.");
        }

        $handle = @fopen($filePath, 'r');
        if ($handle === false) {        
            throw new \InvalidArgumentException("Error: Could not open file '{$filePath}' for reading.");
        }

        $highestBid = -INF;
        $secondHighestBid = -INF;
        $bestAdId = null;
        $lineNumber = 0;
        $validBidsFound = 0;

        while (($line = fgets($handle)) !== false) {
            $lineNumber++;
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = explode(',', $line);

            if (count($parts) !== 2) {
                continue;
            }

            $adIdStr = trim($parts[0]);
            $bidStr = trim($parts[1]);

            if (!ctype_digit($adIdStr)) {
                continue;
            }

            if (!is_numeric($bidStr)) {
                continue;
            }

            $adId = (int)$adIdStr;
            $bid = (float)$bidStr;

            if ($bid < 0) {
                continue;
            }

            $validBidsFound++;

            if ($bid > $highestBid) {
                $secondHighestBid = $highestBid;
                $highestBid = $bid;
                $bestAdId = $adId;
            } elseif ($bid === $highestBid && $adId < $bestAdId) {
                // handle duplicate bid
                $bestAdId = $adId;
            } elseif ($bid > $secondHighestBid && $bid < $highestBid) {            
                $secondHighestBid = $bid;
            }
        }

        fclose($handle);

        if ($bestAdId === null) {
            throw new \RuntimeException("Error: No valid bids found in the file '{$filePath}'.");
        }

        if ($secondHighestBid === -INF && $validBidsFound > 1) {
            $secondHighestBid = $highestBid;
        } elseif ($secondHighestBid === -INF && $validBidsFound <= 1) {
            throw new \RuntimeException("Error: Only one valid bid found. Cannot determine second-highest bid price.");
        }

        return [
            'adId' => (int)$bestAdId,
            'secondBid' => (float)$secondHighestBid
        ];
    }
} 