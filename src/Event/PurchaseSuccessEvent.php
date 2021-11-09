<?php
namespace App\Event;

use App\Entity\Purchase;
use Doctrine\ORM\Query\Expr\Func;
use Symfony\Contracts\EventDispatcher\Event;

class PurchaseSuccessEvent extends Event{
    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function getPurchase(): Purchase{
        return $this->purchase;
    }

    
}