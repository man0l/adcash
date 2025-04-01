<?php

namespace App\Services;

class WordFrequencyService
{
    private string $storageFile;
    private array $wordFrequencies;

    public function __construct(string $storageFile = 'word_frequencies.json')
    {
        $this->storageFile = $storageFile;
        $this->loadFrequencies();
    }

    private function loadFrequencies(): void
    {
        if (file_exists($this->storageFile)) {
            $content = file_get_contents($this->storageFile);
            $this->wordFrequencies = json_decode($content, true) ?? [];
        } else {
            $this->wordFrequencies = [];
        }
    }

    private function saveFrequencies(): void
    {
        file_put_contents($this->storageFile, json_encode($this->wordFrequencies));
    }

    public function processText(string $text): void
    {
        if (empty(trim($text))) {
            throw new \InvalidArgumentException("Text cannot be empty");
        }

        $words = preg_split('/\s+/', strtolower($text), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $word) {
            $this->wordFrequencies[$word] = ($this->wordFrequencies[$word] ?? 0) + 1;
        }

        $this->saveFrequencies();
    }

    public function getAllFrequencies(): array
    {
        return $this->wordFrequencies;
    }

    public function getWordFrequency(string $word): int
    {
        $word = strtolower($word);
        return $this->wordFrequencies[$word] ?? 0;
    }
} 