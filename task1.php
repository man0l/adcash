<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\BidProcessor;

$inputFile = $argv[1];

try {
    $processor = new BidProcessor();
    $result = $processor->findWinningBid($inputFile);    
    echo $result['adId'] . ", " . $result['secondBid'] . "\n";
    exit(0);

} catch (\InvalidArgumentException $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
} catch (\RuntimeException $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
} catch (\Throwable $e) {    
    fwrite(STDERR, "An unexpected error occurred: " . $e->getMessage() . "\n");
    exit(1);
}
