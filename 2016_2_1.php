<?php
class RockPaperScissors {

    public function solve($N, $R, $P, $S) {
        $players = ['R' => $R, 'P' => $P, 'S' => $S]; echo 'before: '; print_r($players);
        for ($i = 0; $i < $N; $i ++) {
            $players = $this->compete($players);
            if ($players === false) return 'IMPOSSIBLE';
        }

        arsort($players); // echo 'after: '; print_r($players);
        $winner = '';
        foreach ($players as $k => $v) { $winner = $k; break; }

        for ($i = 0; $i < $N; $i ++) $winner = $this->expand($winner);
        return $this->adjust($winner);
    }

    public function compete($players) {
        $rule = ['SP' => 'S', 'PS' => 'S', 'PR' => 'P', 'RP' => 'P', 'SR' => 'R', 'RS' => 'R'];
        $winners = ['R' => 0, 'P' => 0, 'S' => 0];
        $num = $players['R'] + $players['P'] + $players['S'];
        for ($i = 0; $i < $num / 2; $i ++) {
            $A = $players['R'] >= $players['P'] ? 'R' : 'P';
            $B = $players['R'] >= $players['P'] ? 'P' : 'R';
            $B = $players[$B] >= $players['S'] ? $B : 'S';

            $winner = $rule[$A.$B];
            $players[$A] --; $players[$B] --; $winners[$winner] ++;
            if ($players[$A] < 0 || $players[$B] < 0) return false;
        }
        return $winners;
    }

    public function expand($w) {
        $rule = ['S' => 'PS', 'R' => 'RS', 'P' => 'PR'];
        $str = ''; $len = strlen($w);
        for ($i = 0; $i < $len; $i ++) $str .= $rule[$w[$i]];
        return $str;
    }

    public function adjust($str) {
        $len = strlen($str);
        if ($len == 1) return $str;
        $A = $this->adjust(substr($str, 0, $len / 2));
        $B = $this->adjust(substr($str, $len / 2));
        return $A < $B ? $A.$B : $B.$A;
    }

}

$R = new RockPaperScissors();
echo $R->solve(3, 4, 2, 2);
//exit;

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
                $R = new RockPaperScissors();
                $r = $R->solve($N[0], $N[1], $N[2], $N[3]);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('../下载/A-small-practice.in','../下载/OUT_1.txt');
$i = new Input('../下载/A-large-practice.in','../下载/OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Date: 18-11-29
 * Time: 下午2:01
 *
 * 你要组织一场剪刀石头布大赛, 比赛组织 N 轮, 有 2^N 个选手参加, 两两比赛, 类似二叉树决出冠军
 * 每个选手都只会出一种拳, 有 R 个选手出 rock, P 个选手出 paper, S 个选手出 scissors
 * 你要安排一个序列让两个出相同拳的选手不会碰上
 *
 * 后来发现, 对于每一个N, 和最后的胜出者(P, R, S), 都只有一种组合
 * 所以可以对于每一个N, 生成最后的序列, 再数数每个出拳的个数, 只有相符的才能胜出
 * 字母顺序相关!! 没有读题, 修正以后通过了
 */