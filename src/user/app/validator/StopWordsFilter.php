<?php

namespace user\app\validator;

class StopWordsFilter
{
    private array $bannedWords;

    public function __construct(array $bannedWords)
    {
        $this->bannedWords = $bannedWords;
    }

    /**
     * Returns true if text contains any banned words
     */
    public function hasBannedWords(string $text): bool
    {
        foreach ($this->bannedWords as $word) {
            if (str_contains($text, $word)) {
                return true;
            }
        }
        return false;
    }
}
