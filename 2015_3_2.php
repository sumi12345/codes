<?php
/**
 * Created by PhpStorm.
 * User: sumi
 * Date: 18-12-5
 * Time: 下午1:59
 *
 * Adamma 是一个气象学家. 每分钟, 他记录下当前温度, 形成一个list: x1, x2, ..., xN
 * 今天他想画一条滑动平均值曲线(sliding average), 选取 K 作为窗口 (smoothing window)
 * 就是说, 把 N 个温度变成 N - K + 1 个平均温度, 每个温度是 xi 到 x(i+K-1) 的平均温度
 * 糟糕的是, 他忘记保存原值了! 但他现在想知道最高和最低温度之间的温度差.
 * 想想最大温差的结果可能有很多个, 那就给一个最小的吧.
 */