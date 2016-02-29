<?php
use App\Helpers\BaseHelper;

class BaseHelperTest extends TestCase
{
    /**
     * Test BaseHelper
     *
     * @return void
     */

    private static function checkBaseGen($num) {
        $toBase32 = BaseHelper::toBase($num, 32);
        $toBase62 = BaseHelper::toBase($num, 62);

        $fromBase32 = BaseHelper::toBase10($toBase32, 32);
        $fromBase62 = BaseHelper::toBase10($toBase62, 62);

        if ($fromBase62 == $num && $fromBase32 == $num) {
            return true;
        }
        return false;
    }
    public function testLogin() {
        $nums = [
            523002,
            1204,
            23,
            0,
            1,
            45
        ];

        foreach ($nums as $n) {
            $this->assertEquals(true, self::checkBaseGen($n));
        }

    }
}
