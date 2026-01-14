<?php

namespace App\Service;

class DatabaseMapper
{
    private $gateway;

    public function __construct(DatabaseGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function findAll(): array
    {
        return $this->gateway->listDbs();
    }
}
