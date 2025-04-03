<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\MazeSolver;

$array1 = [
    [0, 0, 0, 0, 0, 0],
    [1, 1, 1, 1, 1, 0],
    [0, 0, 0, 0, 0, 0],
    [0, 1, 1, 1, 1, 1],
    [0, 1, 1, 1, 1, 1],
    [0, 0, 0, 0, 0, 0],
];

$array2 = [
    [0, 1, 1, 0],
    [0, 0, 0, 1],
    [1, 1, 0, 0],
    [1, 1, 1, 0]
];

$solver = new MazeSolver();

echo $solver->solve($array1) . "\n";
echo $solver->solve($array2) . "\n";