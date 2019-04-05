<?php
ini_set("max_execution_time",  "30");
ini_set("memory_limit","30M");

class Aerobics {
	private $N, $W, $L, $R;
	private $space = array(), $A = array();

	function __construct($n, $w, $l, $r) {
		$this->N = $n;
		$this->W = $w;
		$this->L = $l;
		$this->R = $r;
	}

	function solve() {
		//初始化space
		$o = array('x' => 0, 'y' => 0, 'w' => $this->W, 'l' => $this->L);
		$this->add_space($o);
		//整理学生
		arsort($this->R);
		//分配
		foreach($this->R as $k => $r) {
			$space = $this->get_space($r);
			if(!$space) { echo 'failed'; return false; }
			$this->A[$k] = array('x' => $space['x'] == 0 ? 0 : $space['x'] + $r, 'y' => $space['y'] == 0 ? 0 : $space['y'] + $r);
		}
		return $this->A;
	}

	//为指定的学生分配合适的碎片
	function get_space($r) {
		$space = false;
		foreach($this->space as $k => $arr) {	//找到可分配碎片
			if($k >= 2 * $r) {
				$space = array_pop($this->space[$k]); break;
			} else {
				foreach($arr as $key => $s) if($this->check_fit($s, $r)) {
					$space = $s; unset($this->space[$k][$key]); break;
				}
			}
		}
		if(!$this->space[$k]) unset($this->space[$k]);
		if(!$space) return false;

		//此次分配的大小
		$w = min(2 * $r, $space['w']); $l = min(2 * $r, $space['l']); //echo $w.' '.$l.'<br/>';
		$right_width = $space['w'] - $w; $bottom_length = $space['l'] - $l;

		if($right_width > 0) {
			$right = array('x' => $space['x'] + $w, 'y' => $space['y'], 'w' => $right_width, 'l' => $l);
			$this->add_space($right);
		}
		if($bottom_length > 0) {
			$bottom = array('x' => $space['x'], 'y' => $space['y'] + $l, 'w' => $space['w'], 'l' => $bottom_length);
			$this->add_space($bottom);
		}
		//echo '<br/>'; print_r($this->space); echo '<br/>';
		return $space;
	}

	function add_space($space) {
		$key = min($space['w'], $space['l']); $key = ''.$key;
		if(!isset($this->space[$key])) $this->space[$key] = array();
		array_push($this->space[$key], $space);
	}

	function check_fit($s, $r) {
		$x_corner = ($s['x'] == 0 || $s['x'] == $this->W) ? true : false;
		$y_corner = ($s['y'] == 0 || $s['y'] == $this->L) ? true : false;
		if($x_corner && $y_corner) return true;
		if($x_corner && $s['l'] >= 2 * $r) return true;
		if($y_corner && $s['w'] >= 2 * $r) return true;
		return false;
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
				$conf = explode(' ', trim(fgets($handle)));
				$N = $conf[0]; $W = $conf[1]; $L = $conf[2];
				$R = explode(' ', trim(fgets($handle)));
				$a = new Aerobics($N, $W, $L, $R);
				$arr = $a->solve(); $r = '';
				if($arr) for($i = 0; $i < $N; $i ++) $r .= $arr[$i]['x'].' '.$arr[$i]['y']. ($i == $N - 1 ? '' : ' ');
				echo ($r).'<br/>';
				file_put_contents($this->out_file, ($r).($c == $cases ? "" : "\r\n"), FILE_APPEND);
			}
			fclose($handle);
		}
	}
}

$t = time();
$i = new Input('../下载/B-large-practice.in','../下载/OUT_3.txt');
$i->process();
echo '<br/>execution time: '.(time() - $t).'<br/>';

/*
	测试通过 错误总结
	原：没有检查出space不够分配的情况 --判断right_width和bottom_length
	原方案：不管边缘方向 把所有的圆都安排在矩形内 --case中会出现不够的情况
	修改：判断是否在上或左边缘 如果是 将对应半径改为r并按r分配 --导致过早被分割 且仍然会出现failed
	修改：判断四个边缘 按2r分配 --仍然会出现failed
	修改：如果在四个角 则无条件满足
	因为不是按顺序记录答案 所以输出时不能用foreach

	目前判断方法 (更新条件)
	1. W >= 2R L >= 2R
	2. 靠上边界 L >= 0 (L总是够的)
	   靠左边界 W >= 0 (W总是够的)

	完整判断方法
	1. W >= 2R L >= 2R
	2. 靠上边界 || 靠下边界 L >= R
	   靠左边界 || 靠右边界 W >= R
	3. 靠上边界 && 靠下边界 L >= 0
	   靠左边界 && 靠右边界 W >= 0

	分配时仍按 L = 2R W = 2R 分配

*/
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 19-3-21
 * Time: 下午9:56
 */