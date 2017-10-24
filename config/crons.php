<?php
/**
 * 定时任务配置 分级任务
 */
$crons = [
    [
    	"job" => "phalcon",
        "expression" => "*/1 * * * *",
        "command"    => "schedule test p1 p2 p3",
    ],
    [
    	"job" => "phalcon",
        "expression" => "*/2 * * * *",
        "command"    => "schedule test 1",
    ],
    [
    	"job" => 'system',
    	"expression" => "*/3 * * * *",
        "command"    => "sh path/shell.sh",
    ]
];

return $crons;
