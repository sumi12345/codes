<?php

class BFF {

    public function solve($N, $G) {
        $this->N = $N; // number of kids
        $this->G = $G; // graph

        $this->RG = []; // reversed graph
        for ($i = 1; $i <= $this->N; $i ++) {
            $to = $this->G[$i];
            if (!isset ($this->RG[$to])) $this->RG[$to] = [];
            $this->RG[$to][$i] = 1;
        }

        $a = $this->findCircle();
        $b = $this->findMutualBFF();
        return $a > $b ? $a : $b;
    }

    public function findCircle() {
        $this->R = []; // reached node's length

        $max = 0;        
        for ($i = 1; $i <= $this->N; $i ++) {
            if (isset ($this->R[$i])) continue;

            foreach ($this->R as $k => $v) $this->R[$k] = 0;
            $this->S = [$i];  // stack
            $this->R[$i] = 1; // reached
            $len = $this->DFS_circle();
            $max = $len > $max ? $len : $max;
        }
        return $max;
    }

    private function findMutualBFF() {
        $this->R = [];
        $len = 0;

        for ($i = 1; $i <= $this->N; $i ++) {
            if (isset($this->R[$i])) continue;
            
            $bff = $this->G[$i];
            $bffsbff = $this->G[$bff];
            if ($i == $bffsbff && $i < $bff) {
                unset($this->RG[$i][$bff]);
                unset($this->RG[$bff][$i]);
                $len += $this->likeLink($i) + $this->likeLink($bff) + 2;
            }
        }

        return $len;
    }

    public function DFS_circle() {
        $p = $this->S[count($this->S) - 1];
        $n = isset ($this->G[$p]) ? $this->G[$p] : false;
        echo "p = $p; n = $n; ";

        if ($n && isset ($this->R[$n]) && $this->R[$n] == 0) {  // not available
            echo "not available!\n";
            return 0;
        } elseif ($n && isset ($this->R[$n])) {  // circle found
            echo "circle found!\n";
            return $this->R[$p] + 1 - $this->R[$n];
        } elseif ($n) {                    // next node
            echo "move on\n";
            array_push($this->S, $n);
            $this->R[$n] = $this->R[$p] + 1;
        } else {                           // no next node
            echo "pop\n";
            array_pop($this->S);
        }

        return $this->DFS_circle();
    }

    public function likeLink($i) {
        echo "----likeLink of $i: \n";
        $this->R = [$i => 0];
        $this->S = [$i];

        $this->DFS();

        $max = 0;
        foreach ($this->R as $n => $depth) $max = $depth > $max ? $depth : $max;
        return $max;
    }

    private function DFS() {
        if (empty($this->S)) return;

        $node = $this->S[count($this->S) - 1];
        print_r($this->S);
        if (isset($this->RG[$node])) {
            foreach ($this->RG[$node] as $to => $v) {
                $this->S[] = $to;
                $this->R[$to] = $this->R[$node] + 1;
                $this->DFS();
            }
        }
        array_pop($this->S);
    }
}

$B = new BFF();
echo $B->solve(10, explode(' ', '0 7 8 10 10 9 2 9 6 3 3'));