<?php

namespace ES\App\Modules\Blog\Model;

/**
 * ModeratorInterface short summary.
 *
 * ModeratorInterface description.
 *
 * @version 1.0
 * @author ragus
 */
interface ModeratorInterface
{
    public function moderate(string $text): string;
}