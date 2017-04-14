<?php
declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use MXJ\Restructure\Ent;

$arr = [
    1 => [0, 1, 2],
    2 => 3,
    3 => [
        [4, 5],
        6
    ]
];

$arr2 = [
    [
        'name'     => 'Product One',
        'currency' => [
            [
                'alpha' => 'USD',
                'iso'   => '840',
            ],
            [
                'alpha' => 'EUR',
                'iso'   => '978',
            ],
        ],
    ],
    [
        'name'     => 'Product Two',
        'currency' => [
            [
                'alpha' => 'EUR',
                'iso'   => '978',
            ],
        ],
    ],
    [
        'name'     => 'Product Two',
        'currency' => [
            [
                'alpha' => 'RUB',
                'iso'   => '643',
            ],
            [
                'alpha' => 'USD',
                'iso'   => '840',
            ],
        ],
    ],
];

$i = new Ent($arr2);

foreach ($i->remap(['name']) as $key => $value) { //'currency' => ['iso', 'alpha'],
    var_dump($key, $value);
}