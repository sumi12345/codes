<?php
ini_set("max_execution_time","3");
ini_set("memory_limit","3M");

class Kiddie_pool {

    /**
     * @param $N int N个水源
     * @param $V float 目标体积
     * @param $X float 目标温度
     * @param $R array 各水源的最大流量
     * @param $C array 各水源的温度
     */
    public function solve($N, $V, $X, $R, $C) {
        // 如果所有水源都以最大流量放水 1 时间, 得到的体积和温度
        $temp = $this->calculate_temp($R, $C);

        // 如果水温偏低/偏高 调整最冷/最热水源的流量
        $t = $temp['t'];
        $v = $temp['v'];
        if ($t / $v < $X) asort($C);
        elseif ($t / $v > $X) arsort($C);
        if ($t / $v == $X) return $V / $v;

        // 调整流量
        foreach ($C as $key => $c) {
            if ($t / $v == $X) break;
            $this->ajust_temp($X, $t, $v, $R[$key], $c);
        }

        // 没办法达到目标水温
        if ($v == 0) return 'IMPOSSIBLE';

        // 如果已经达到温度 计算时间
        return $V / $v;
    }

    // 计算温度
    private function calculate_temp($R, $C) {
        $v = 0;
        $t = 0;
        foreach ($C as $key => $temp) {
            $v += $R[$key];
            $t += $temp * $R[$key];
        }
        return array('t' => $t, 'v' => $v);
    }

    // 调整温度
    private function ajust_temp($X, &$t, &$v, $R, $C) {
        if ($X == $C) return true;
        $t -= $R * $C;
        $v -= $R;
        $r = ($t - $X * $v) / ($X - $C);
        if ($r >= 0) {
            $t += $r * $C;
            $v += $r;
        }
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
            $P = new Kiddie_pool();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $N = intval($conf[0]); $V = floatval($conf[1]); $X = floatval($conf[2]); $R = array(); $C = array();
                for ($i = 0; $i < $N; $i ++) {
                    $row = explode(' ', trim(fgets($handle)));
                    $R[] = floatval($row[0]); $C[] = floatval($row[1]);
                }
                $r = $P->solve($N, $V, $X, $R, $C);
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
