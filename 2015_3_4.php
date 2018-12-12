<?php
class LogSet {

    public function solve($N, $E, $F) {
        $cnt = array_combine($E, $F); echo 'solve: '; print_r($cnt);
        $S = $this->findMagnitude($cnt); echo 'S: '; print_r($S);
        if ($E[0] >= 0) return implode(' ', $S);

        $DP = [];
        $possible = $this->subsetSum(count($S), $E[$N - 1], $S, $DP);
        if (!$possible) return 'IMPOSSIBLE'; // print_r($DP);

        $R = []; $v = $E[$N - 1];
        for ($n = count($S); $n > 0; $n --) {
            echo 'findRoad('.$n.', '.$v.') = '.$DP[$n][$v]."\n";
            $R[] = ($DP[$n][$v] == 'T' ? 1 : -1) * $S[$n - 1];
            if ($DP[$n][$v] == 'T') $v -= $S[$n - 1];
        }

        sort($R);
        return implode(' ', $R);
    }

    // 前 N 个数字中有没有相加可以等于 V 的子集
    public function subsetSum($N, $V, $S, &$DP) {
        echo 'subsetSum('.$N.', '.$V.')|'.$S[$N - 1]."\n";
        if ($N == 1) {
            $DP[$N][$V] = $V == $S[0] ? 'T' : ($V == 0 ? 'F' : '.');
        } elseif (!isset($DP[$N][$V])) {
            $v = $S[$N - 1];
            if ($this->subsetSum($N - 1, $V, $S, $DP)) $DP[$N][$V] = 'F';
            elseif ($this->subsetSum($N - 1, $V - $v, $S, $DP)) $DP[$N][$V] = 'T';
            else $DP[$N][$V] = '.';
        }

        echo 'subsetSum('.$N.', '.$V.')|'.$DP[$N][$V]."\n";
        return $DP[$N][$V] == '.' ? false : true;
    }

    // 找绝对值的集合
    public function findMagnitude($cnt) {
        $S = [];
        for ($K = 0; $K <= 60; $K ++) { // echo "before: $K\n"; print_r($cnt);
            // 计算总数量
            $n = 0;
            foreach ($cnt as $k => $v) $n += $v;
            // 找出最大和次大的数字
            $max = '.'; $second = '.';
            foreach ($cnt as $k => $v) { $second = $max; $max = $k; if ($v > 1) $second = $k; }
            // 返回条件
            if ($n == 2) if (empty($S)) return [$max == 0 ? abs($second) : abs($max)]; // 要保证S里都是正数
            if ($n < 2) return $S;
            // 找出最大的和次大的数字, 计算差值 d, S'中所有数字减去 d
            $d = $max - $second; // echo '|'.$d.', '.$n.'|';
            $S[] = $d;
            // 相差 d 的数字两两配对, 保留一组
            if ($d == 0) {      // 如果 d=0, 所有数字减半
                foreach ($cnt as $k => $v) $cnt[$k] = $v / 2;
            } else {
                foreach ($cnt as $k => $v) {     // echo '|'.$k.'|';
                    if ($k > $max - $d) break;   // 小的那组的最大值是 max - d
                    if ($cnt[$k] == 0) continue; // 已经被 k - d 匹配完了, 不做任何操作. 不能用 v.
                    $cnt[$k + $d] -= $cnt[$k];   // 不能用 v, 因为 v 是初始值
                }
                foreach ($cnt as $k => $v) if ($cnt[$k] == 0) unset($cnt[$k]);
            }
        }
    }

}

$L = new LogSet();
echo $L->solve(12, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 1, 3, 3, 4, 4, 4, 4, 3, 3, 1, 1]); echo "\n";
echo $L->solve(2, [0, 9999], [1, 1]); echo "\n";
echo $L->solve(13, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [4, 8, 4, 8, 20, 16, 8, 16, 20, 8, 4, 8, 4]); echo "\n";
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
                $N = trim(fgets($handle));
                $E = explode(' ', trim(fgets($handle)));
                $F = explode(' ', trim(fgets($handle)));
                $L = new LogSet();
                $r = $L->solve($N, $E, $F);
                echo ($r)."\n";
                file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
            }
            fclose($handle);
        }
    }
}

$t = time();

//$i = new Input('../下载/D-small-practice.in','../下载/OUT_3.txt');
$i = new Input('../下载/D-large-practice.in','../下载/OUT_3.txt');
$i->process();

echo "\n".'execution time: '.(time() - $t)."\n";
echo "\n".'memory peak usage: '.(memory_get_peak_usage() / 1024 / 1024);

/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-5
 * Time: 下午1:50 到第二天 15:00
 *
 * 集合 S 的幂集(power set) 是 S 的所有子集的集合.
 * 集合 S 由整数组成, 找到它的幂集, 把幂集中每个集合替换为这个集合中所有元素的和, 记为 S'
 * 给你 S', 能反推出 S 吗? 如果有多个可能 S, 找到最小的那个.
 *
 * 记 x 为 S' 的最大元素. y 为 S' 的次大元素.
 * 则 x 是所有正数的和, y 可能是所有正数减去最小正数, 也可能是所有正数加上绝对值最小的负数.
 *
 * 所以 x-y=d, d 要么是最小正数, 要么是绝对值最小的负数.
 * 比如一个四个元素的集合, 它的幂集是:
 * {},
 * {A}, {B}, {C}, {D},
 * {AB}, {AC}, {AD}, {BC}, {BD}, {CD},
 * {ABC}, {ABD}, {ACD}, {BCD},
 * {ABCD}
 * 如果 A=d, 从幂集中的所有集合减去A, 结果是:
 * {-d},
 * {}, {B-d}, {C-d}, {D-d}
 * {B}, {C}, {D}, {BC-d}, {BD-d}, {CD-d},
 * {BC}, {BD}, {CD}, {BCD-d},
 * {BCD}
 * 正好是 BCD 的幂集, 和 BCD 的幂集-d.
 * 所以将 S' 中的所有值都-d, 相差d的两个数两两配对, 保留一组, 再按照相同的原则, 找出 BCD 中绝对值最小的那个, 以此类推.
 * 如果是小数据集, S 中都是正数, 找出绝对值集合即可.
 * 如果是大数据集, 我们要确定 S 中每一个数的符号, 即找出一个子集, 让他们是正数, 它们的和为 S' 的最大值. 其他的元素即记为负数.
 * 这是一个子集和问题(Subset Sum problem). 用动态规划可以解决. 因为 S 中的数据不大(<=60), 所以可能结果不会太多, 所以动态规划的状态不会太多.
 *
 * 动态规划, 计算前 N 个数中能不能有一个子集的和是 V.
 * OPT(n, v)=OPT(n-1, v) 如果第n个数不在集合里
 * OPT(n-1, v-vn) 如果第n个数在集合里
 * 因为 S 是绝对值升序, 所以从第n个数开始, 先找不在集合的, 再找在集合的, 找到一条路径可以返回true即可.
 */