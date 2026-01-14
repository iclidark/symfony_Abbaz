<?php

namespace App\Tests;

use App\Service\DatabaseGateway;
use App\Service\DatabaseMapper;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseMapperTest extends TestCase
{
    public function testRealDatabase()
    {
        // Ce test nécessite une base de données PostgreSQL en cours d'exécution.
        // Il est marqué comme sauté (skipped) car nous ne pouvons pas garantir que la DB est disponible.
        if (!extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped('L\'extension pdo_pgsql n\'est pas disponible.');
        }

        try {
            $pdo = new PDO('pgsql:host=127.0.0.1;dbname=postgres', 'postgres', 'password');
        } catch (\PDOException $e) {
            $this->markTestSkipped('Impossible de se connecter à la base de données PostgreSQL: ' . $e->getMessage());
        }

        $gateway = new DatabaseGateway($pdo);
        $mapper = new DatabaseMapper($gateway);

        $this->assertIsArray($mapper->findAll());
    }

    public function testStubDatabase()
    {
        $stub = $this->createStub(DatabaseGateway::class);
        $stub->method('listDbs')
             ->willReturn(['db1', 'db2']);

        $mapper = new DatabaseMapper($stub);
        $result = $mapper->findAll();

        $this->assertCount(2, $result);
    }

    public function testMockDatabase()
    {
        $mock = $this->createMock(DatabaseGateway::class);
        $mock->expects($this->once())
             ->method('listDbs')
             ->willReturn(['db1', 'db2', 'db3']);

        $mapper = new DatabaseMapper($mock);
        $result = $mapper->findAll();

        $this->assertCount(3, $result);
    }
}
