<?php
declare(strict_types = 1);

require __DIR__ . '../vendor/autoload.php';

use MXJ\Restructure\Ent;

$arr = [
    1 => [0, 1, 2],
    2 => 3,
    3 => [
        [4, 5],
        6
    ]
];

$i = new Ent($arr);

foreach ($i->filter(function ($key, $value, $level) {
    return $level === 2;
}) as $key => $value) {
    var_dump($key, $value);
}