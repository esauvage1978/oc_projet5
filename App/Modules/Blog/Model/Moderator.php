<?php

namespace ES\App\Modules\Blog\Model;

class Moderator implements ModeratorInterface
{
    private $replacements;

    public function __construct(array $replacements = [])
    {
        $this->replacements = $replacements;
    }

    public function moderate(string $text): string
    {
        return strtr($text, $this->replacements);
    }
}