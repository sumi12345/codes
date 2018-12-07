<?php
ini_set("max_execution_time","1");
ini_set("memory_limit","1M");

class Bilingual {

    /**
     * @param $N int 有多少句子
     * @param $S array 句子
     */
    public function solve($N, $S) {
        //print_r($S); exit;

        $G = array();  // 图
        foreach ($S as $skey => $sentence) {
            foreach ($sentence as $word) {
                $G[$skey][$word] = 1;
                $G[$word][$skey] = 1;
            }
        }
        //print_r($G); exit;

        $routes = 0;
        while (true) {
            $r = $this->BFS($G);
            if ($r === false) break;
            $routes ++;
        }

        return $routes;
    }

    public function BFS(&$G) {
        $visited = array();
        $queue = array(0);

        while(!empty($queue)) {
            $v = array_shift($queue);

            foreach ($G[$v] as $v2 => $e) if (!isset($visited[$v2])) {
                $visited[$v2] = $v;
                $queue[] = $v2;

                if ($v2 == 1) {
                    $this->clear_routes($visited, $G);
                    return true;
                }
            }
        }

        return false;
    }

    private function clear_routes(&$visited, &$G) {
        echo 'routes: ';
        $v = 1;
        while ($v) {
            echo $v.'->';
            $prev = $visited[$v];
            unset($G[$v][$prev], $G[$prev][$v]);
            $v = $prev;
        }
        echo "0\n";
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
            $B = new Bilingual();
            for($c = 1; $c <= $cases; $c ++) {
                echo 'Case #'.$c.': ';
                file_put_contents($this->out_file, 'Case #'.$c.': ', FILE_APPEND);
                $conf = explode(' ', trim(fgets($handle)));
                $N = intval($conf[0]); $S = array();
                for ($i = 0; $i < $N; $i ++) $S[] = explode(' ', trim(fgets($handle)));
                $r = $B->solve($N, $S);
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
