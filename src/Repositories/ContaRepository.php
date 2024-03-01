<?php

namespace Rinha\Repositories;

use React\Promise\PromiseInterface;
use Rinha\Database\DatabaseContext;
use Rinha\Database\Interfaces\IDatabaseStrategy;
use Rinha\Entities\Conta;
use Rinha\Repositories\Interfaces\IContaRepository;

class ContaRepository implements IContaRepository
{
    private $db;

    public function __construct(IDatabaseStrategy $db)
    {
        $databaseContext = new DatabaseContext($db);
        $this->db = $databaseContext;
    }

    /** @return PromiseInterface<?Conta> **/
    public function findByClienteId(int $clienteId): PromiseInterface
    {
        return $this->db->findByClienteId($clienteId)->then(function (?Conta $conta) {
            return $conta;
        });
    }
}
