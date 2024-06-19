<?php
namespace helpers;

/**
 * Words
 *
 * @author Volkov Grigorii
 */
class Words
{
    public static function forbiddenWords(): array
    {
        return ['word1', 'word2', 'word3'];
    }

    public static function forbiddenDomains(): array
    {
        return ['@dom1.ru', '@dom2.ru', '@dom3.ru'];
    }
}
