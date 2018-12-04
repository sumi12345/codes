<?php

class Jamcoin {

    public function solve($N, $J) {
        $sets = [];
        $found = 0;
        $o = pow(2, $N - 1) + 1;

        while ($found < $J && $o < pow(2, $N)) {
            if ($divisors = $this->check($o)) {
                $sets[] = decbin($o).' '.implode(' ', $divisors);
                $found ++;
            }
            $o += 2;
        }
        echo 'jamcoin found: '; print_r($sets);
        return implode("\n", $sets);
    }

    private function check($o) {
        $divisors = [];

        for ($i = 2; $i <= 10; $i ++) {
            $d = $this->checkOnBase($o, $i); 
            if (! $d) return false;
            $divisors[] = $d;
        }

        echo "\n".decbin($o);
        foreach($divisors as $d) echo ' '.$d;
        return $divisors;
    }

    private function checkOnBase($o, $base) {
        echo "\n".'checkOnBase('.decbin($o).', '.$base.')';
        $n = 0;
        $b = 1;

        while ($o > 0) {
            $n += ($o % 2) * $b;
            $b = $b * $base;
            $o = floor($o / 2);
        }
        echo "\nnumber is: ".number_format($n, 0, '', '');

        $d = floor(sqrt($n));
        for($i = 2; $i <= $d; $i ++) if ($n % $i == 0) {
                echo "\n$n % $i = 0";
                return $i;
        }
        return false;
    }
    
}

$J = new Jamcoin();
echo $r = $J->solve(16, 50); // 在32位电脑上, 10的16次方超过32位整数的表示范围
file_put_contents('../下载/OUT_3.txt', "Case #1:\n".$r);

/**
 * 一个 jamcoin 是一个长度为 N (N>=2) 的字符串. 它满足以下条件:
 * 1. 每一位是 0 或 1; 2. 第一位是 1, 最后一位是 1;
 * 3. 把这个数字表示为 2-10 进制, 结果都不是质数
 * 我们听说有社区使用 jamcoin 当作货币. 所以出于礼貌, 对这个 jamcoin 的 2-10 进制数,
 * 我们都提供一个因数(不等于 1 和 jamcoin 值) 来证明这个 jamcoin 是合法的
 * 要求提供 J 个不同的, 长度为 N 的 jamcoin, 并给出对应的 2-10 进制的因数
 *
 * 思路:
 * 小数据集: 枚举 jamcoin 的可能值, 用试除法(trial division), 从 1 试到 sqrt(jamcoin)
 * 大数据集: N=32 的时候, 10 的 32 次方已经不能表示为 64 位整数了.
 * 我们看看 N=16 的前 50 个结果, 其中 11 个因数是
 * 2=>3, 3=>2, 4=>5, 5=>2, 6=>7, 7=>2, 8=>3, 9=>2, 10=>11
 * 规律: b 进制的因数是 (b + 1) 的最小质因数, 而 b + 1 在 b 进制数中表示为 11
 * 根据整除规则(divisibility rule)(如: 3 的整除规则是, 每一位相加可以被 3 整除)
 * 11 的整除规则有一条: 奇数位相加, 减去偶数位相加, 结果能被 11 整除.
 * (Form the alternating sum of the digits. The result must be divisible by 11)
 * 这条规则可以被扩展: 0-1字符串中, 只要奇数位的1个数等于偶数位的1个数, 就能被11整除
 * 另外, 满足正则 11(0|11)*11 的字符串, 比如, 对于任意进制数 11011 = 1001 * 11 (没有进位)
 * 有趣的是, 如果你抓一个小数据集的 jamcoin, 重复2遍, 新的字符串也是符合条件的,
 * 因为在一个 jamcoin 后面加上他自己, 等于乘以 10...01, 所有的因数都还生效
 */