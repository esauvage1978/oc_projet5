<?php

namespace ES\App\Modules\Blog\Model;

class ModeratorPhoneNumber implements ModeratorInterface
{
    /** @var ModeratorInterface */
    private $decorated;

    static private $phonePattern = '/[0-9]{10}/';

    public function __construct(ModeratorInterface $baseModerator)
    {
        $this->decorated = $baseModerator;
    }

    public function moderate(string $text): string
    {
        return preg_replace(
            self::$phonePattern,
            '**********',
            $this->decorated->moderate($text)
        );
    }
}