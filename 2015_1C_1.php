<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Brattleship {

    public function solve($R, $C, $W) {
        $r = $R * floor($C / $W) + $W;  // 找到一个 hit, 并尝试直到 miss
        if ($C % $W == 0) $r --;        // 如果在边角, 这行不用试错了
        return $r;
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
            $B = new Brattleship();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $r = $B->solve($conf[0], $conf[1], $conf[2]);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

$i = new Input('IN.txt','OUT.txt');
//$i = new Input('A-small-practice.in','OUT_1.txt');
//$i = new Input('A-large-practice.in','OUT_1.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t);
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * 你和你的弟弟在完一个简化版的战舰游戏.
 * 棋盘是一个 R 行 C 列的方阵. 游戏全程你都闭眼. 你弟弟会把一艘 1xW 的船横着放在棋盘上.
 * 每一回合, 你说一个格子, 你弟弟告诉你船有没有在格子上.
 * 当你列举完船所在的所有格子, 游戏结束, 船沉了. 分数是游戏进行的回合数. 目标是最小化分数.
 * 本来船不应该移动. 但你知道你弟弟一定会作弊. 只要船是横的, 在棋盘上, 和之前的信息不冲突.
 * 你弟弟也知道你知道他会作弊. 如果你们都按最优策略玩游戏, 你能保证达到的最低分数是多少?
 */