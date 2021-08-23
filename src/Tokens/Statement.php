<?php

namespace Imanghafoori\SearchReplace\Tokens;

use Imanghafoori\SearchReplace\Stringify;
use Imanghafoori\SearchReplace\TokenCompare;

class Statement
{
    public static function is($pToken)
    {
        return TokenCompare::is($pToken, '<statement>');
    }

    public static function mustStart($tToken, $tokens, &$startFrom, &$placeholderValues)
    {
        [$_value, $startFrom] = self::readExpression($startFrom, $tokens);
        $placeholderValues[] = $_value;
    }

    public static function readExpression($i, $tokens)
    {
        $level = 0;
        $collected = [];
        $line = 1;

        for ($k = $i; true; $k++) {
            $nextToken = $tokens[$k] ?? '_';
            $collected[] = $nextToken;

            if ($nextToken === ';' && $level === 0) {
                $value = [T_STRING, Stringify::fromTokens($collected), $line];

                return [$value, $k];
            }

            if (\in_array($nextToken[0], ['[', '(', '{', T_CURLY_OPEN], true)) {
                $level++;
            }

            if (\in_array($nextToken[0], [']', ')', '}'], true)) {
                $level--;
            }

            isset($nextToken[2]) && $line = $nextToken[2];
        }
    }
}