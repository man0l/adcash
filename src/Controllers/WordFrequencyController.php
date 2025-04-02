<?php

namespace App\Controllers;

use App\Services\WordFrequencyService;
use InvalidArgumentException;

class WordFrequencyController
{
    private WordFrequencyService $service;

    public function __construct(WordFrequencyService $service)
    {
        $this->service = $service;
    }

    public function processText(array $input): array
    {
        if (!isset($input['text'])) {
            throw new InvalidArgumentException('Text field is required');
        }

        $this->service->processText($input['text']);
        return ['status' => 'success', 'message' => 'Text processed successfully'];
    }

    public function getWordFrequency(string $word): array
    {
        $frequency = $this->service->getWordFrequency($word);

        if ($frequency === 0) {
            throw new InvalidArgumentException('Word not found');
        }
        
        return ['word' => $word, 'frequency' => $frequency];
    }

    public function getAllFrequencies(): array
    {
        return $this->service->getAllFrequencies();
    }
} 