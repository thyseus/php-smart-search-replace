<?php

namespace Imanghafoori\SearchReplace\Tests;

use Imanghafoori\LaravelMicroscope\Tests\BaseTestClass;
use Imanghafoori\SearchReplace\Searcher;

class StatementTest extends BaseTestClass
{
    /** @test */
    public function statement()
    {
        $patterns = [
            '$user = "<statement>"' => ['replace' => '"<1>"'],
        ];
        ////////////////////////////////////

        $startCode = '<?php $user = function () { $a = 1; $b = "end"; };';
        $resultCode = '<?php function () { $a = 1; $b = "end"; };';

        [$newVersion, $replacedAt] = Searcher::searchReplace($patterns, token_get_all($startCode));

        $this->assertEquals($resultCode, $newVersion);
        $this->assertEquals([1], $replacedAt);

        ////////////////////////////////////
        $patterns = [
            '"<statement>"' => ['replace' => ''],
        ];

        $startCode = '<?php $user = where(function () { $a = 1; $a; });';
        $resultCode = '<?php ';

        [$newVersion, $replacedAt] = Searcher::searchReplace($patterns, token_get_all($startCode));

        $this->assertEquals($resultCode, $newVersion);
        ////////////////////////////////////
        $patterns = [
            '"<statement>""<statement>"' => ['replace' => ''],
        ];
        $startCode = '<?php $user = where(function () { $a = 1; $a; }); $a = 1;';
        $resultCode = '<?php ';

        [$newVersion, $replacedAt] = Searcher::searchReplace($patterns, token_get_all($startCode));

        $this->assertEquals($resultCode, $newVersion);
        $this->assertEquals([1], $replacedAt);
    }

    /** @test */
    public function statement_2()
    {
        $patterns = [
            '"<statement>"$a = 1;' => ['replace' => '"<1>"'],
        ];
        $startCode = '<?php $user = where(function () { $a = 1; $a; }); $a = 1;';
        $resultCode = '<?php $user = where(function () { $a = 1; $a; });';

        [$newVersion, $replacedAt] = Searcher::searchReplace($patterns, token_get_all($startCode));

        $this->assertEquals($resultCode, $newVersion);
        $this->assertEquals([1], $replacedAt);
    }

}
