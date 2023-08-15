<?php 

namespace App\Services;

class LearnLevels {

    /**
     * Maps the learn level to a recommended interval
     */
    public const LEARN_LEVELS = [
        0 => 0,
        1 => 0,
        2 => 2,
        3 => 4,
        4 => 7,
        5 => 14,
        6 => 30,
    ];

    /**
     * Maximum learn level that can be reached
     */
    public const MAX_LEARN_LEVEL = 6;
}