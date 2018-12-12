<?php
class Dragon {
    public function solve($hd, $ad, $hk, $ak, $b, $d) { echo 'solve: hd='.$hd.', ad='.$ad.', hk='.$hk.', ak='.$ak.', b='.$b.', d='.$d."\n";
        // 一回合击败对方的情况
        if ($hk - $ad <= 0) return 1;

        // 被一回合击败的情况
        if ($hd - ($ak - $d) <= 0) return 'IMPOSSIBLE';

        // 先考虑至少要多少回合击败骑士
        $turn = ceil($hk / $ad);
        if ($b > 0) for ($buff = ceil(($hk - $ad) / $b); $buff >= 0; $buff --) {
            $attack = ceil($hk / ($ad + $buff * $b));
            if ($attack + $buff < $turn) $turn = $attack + $buff;
        }
        if ($ak == 0) return $turn;

        // 总共需要多少回合
        $total = -1;
        // 考虑 debuff 多少次需要多少回合, 直接模拟
        $T = 0; $h = $hd; $a = $ak;
        $max_debuff = $d == 0 ? 0 : ceil($ak / $d);
        // 不进行 debuff 的情况
        $cure = $this->getCure($h, $a, $hd, $turn);
        if ($cure != -1) $total = $T + $turn + $cure;
        // debuff i 次的情况
        for ($i = 1; $i <= $max_debuff; $i ++) {
            if ($h - ($a - $d) <= 0) { $h = $hd; $h -= $a; $T ++; }  // 就算减攻击力也会被打死, 被迫治疗一回合
            $a -= $d; if ($a < 0) $a = 0; $h -= $a; $T ++;           // 减攻击力一回合
            $cure = $this->getCure($h, $a, $hd, $turn);
            if ($cure != -1) { $t = $T + $turn + $cure; if ($t < $total || $total == -1) $total = $t; }
        }
        return $total == -1 ? 'IMPOSSIBLE' : $total;
    }

    // 考虑 debuff 之后的攻击阶段, 需要多少回合治疗
    private function getCure($h, $a, $hd, $turn) { echo 'getCure(h = '.$h.', a = '.$a.')'."\n";
        $cure = 0;
        for ($j = 0; $j < $turn - 1; $j ++) {          // 最后一个回合不需要考虑治疗
            if ($h - $a <= 0) { $h = $hd; $h -= $a; $cure ++; } // 插入一个治疗回合
            $h -= $a;
            if ($h <= 0) return -1; // 必须连续 2 次治疗, 进行 i 次 debuff 的方案不可行
        }
        return $cure;
    }
}

class Input {
    private $in_file;
    private $out_file;

    function __construct($i, $o) {
        $this->in_file = $i;
        $this->out_file = $o;
    }

    function process() {
        $handle = fopen($this->in_file, "r");
        if ($handle) {
            $cases = intval(fgets($handle, 32));
            file_put_contents($this->out_file, '');
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $N = explode(' ', trim(fgets($handle)));
                $D = new Dragon(); //if ($c != 3) continue;
                $r = $D->solve($N[0], $N[1], $N[2], $N[3], $N[4], $N[5]);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

$i = new Input('../下载/C-small-practice.in','../下载/OUT_3.txt');
//$i = new Input('../下载/C-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-12
 * Time: 下午8:04
 */