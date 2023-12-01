<?php

interface TaxiFactory
{
    public function createTaxi();
}

interface Taxi
{
    public function getModel();

    public function getPrice();
}

class EconomyTaxiFactory implements TaxiFactory
{
    public function createTaxi()
    {
        return new EconomyTaxi("fiat");
    }
}

class StandardTaxiFactory implements TaxiFactory
{
    public function createTaxi()
    {
        return new StandardTaxi("toyota");
    }
}

class LuxuryTaxiFactory implements TaxiFactory
{
    public function createTaxi()
    {
        return new LuxuryTaxi("mercedes");
    }
}

class EconomyTaxi implements Taxi
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
        return 10;
    }
}

class StandardTaxi implements Taxi
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
        return 35;
    }
}

class LuxuryTaxi implements Taxi
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
        return 89.99;
    }
}

$client = "economy";

if ($client === "economy") {
    $factory = new EconomyTaxiFactory();
} elseif ($clientType === "standard") {
    $factory = new StandardTaxiFactory();
} elseif ($client === "luxury") {
    $factory = new LuxuryTaxiFactory();
} else {
    die("Невірний тип таксі");
}

$taxi = $factory->createTaxi();

echo "машинa: " . $taxi->getModel() . "\n";
echo "ціна: €" . $taxi->getPrice() . "\n";

