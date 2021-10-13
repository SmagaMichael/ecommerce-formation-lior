<?php

namespace App\Taxes;


class Detector
{
    protected $amount;
    protected $seuil;

    public function __construct(int $seuil)
    {
        $this->seuil = $seuil;
    }

    public function detect(int $amount)
    {

        if ($amount > $this->seuil) {
            return true;
        }
        if ($amount <= $this->seuil) {
            return false;
        }
    }
}
