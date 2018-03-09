<?php

/**
 * 公用文件
 *
 * @filename  common.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:07:15
 * @copyright © 2016-2018 copyright AA笔记
 */
require '../config/config.php';

function getOrder($prefix = '')
{
    return $prefix . date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function addLog($string)
{
    file_put_contents('../log/pay_log.txt', $string . PHP_EOL, FILE_APPEND);
}
