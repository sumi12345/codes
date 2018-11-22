<?php

class Jamcoin {

    public function solve($N, $J) {
        $found = 0;
        $o = pow(2, $N - 1) + 1;

        while ($found < $J && $o < pow(2, $N)) {
            if ($this->check($o)) $found ++;
            $o += 2;
        }
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
        return true;
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
$J->solve(6, 10);

/* 
   大数的模除计算会出错
 */