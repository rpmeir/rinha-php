<?php

namespace Rinha\Database;

use React\Promise\PromiseInterface;
use Rinha\Database\Interfaces\IDatabaseStrategy;
use Rinha\Entities\TransacaoDTO;

class DatabaseContext
{
    private $db;

    public function __construct( IDatabaseStrategy $db )
    {
        $this->db = $db;
    }

    public function setDatabase(IDatabaseStrategy $db): void
    {
        $this->db = $db;
    }

    public function findByClienteId(int $id): PromiseInterface
    {
        return $this->db->findByClienteId($id);
    }

    public function addTransaction(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        return $this->db->addTransaction($conta_id, $transacaoDTO);
    }

    public function lastTenTransactions(int $contaId): PromiseInterface
    {
        return $this->db->lastTenTransactions($contaId);
    }
}
