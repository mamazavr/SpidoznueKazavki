<?php


class taxi
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getPrice()
    {
        return 0;
    }
}
class EconomyTaxi extends taxi
{
    public function getPrice()
    {
        return 10;
    }
}
class StandardTaxi extends taxi
{
    public function getPrice()
    {
        return 35;
    }
}

class LuxuryTaxi extends taxi
{
    public function getPrice()
    {
        return 89.99;
    }
}

$client = "luxury";

if ($client === "economy") {
    $taxi = new EconomyTaxi("fiat");
} elseif ($client === "standard") {
    $taxi = new StandardTaxi("toyota");
} elseif ($client === "luxury") {
    $taxi = new LuxuryTaxi("mercedes");
} else {
    die("Невірний тип таксі");
}

echo "машинa: " . $taxi->getModel() . "\n";
echo "ціна: €" . $taxi->getPrice() . "\n";


