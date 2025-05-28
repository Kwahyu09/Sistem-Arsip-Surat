<?php

namespace App\Models;

class Rekap
{
    public int $year;
    public int $incomingTotal;
    public int $outgoingTotal;

    public function __construct(int $year, int $incomingTotal = 0, int $outgoingTotal = 0)
    {
        $this->year = $year;
        $this->incomingTotal = $incomingTotal;
        $this->outgoingTotal = $outgoingTotal;
    }
}
