<?php

namespace Rinha\Repositories;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Repositories\Interfaces\IContaRepository;

class ContaRepository implements IContaRepository
{
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /** @return PromiseInterface<?Conta> **/
    public function findByClienteId(int $clienteId): PromiseInterface
    {
        return $this->db->query(
            'SELECT cliente_id, id, limite, saldo FROM contas WHERE cliente_id = ?',
            [$clienteId]
        )->then(
            function (QueryResult $result) {
                if (count($result->resultRows) === 0) {
                    return null;
                }

                return new Conta(
                    $result->resultRows[0]['id'],
                    $result->resultRows[0]['cliente_id'],
                    $result->resultRows[0]['limite'],
                    $result->resultRows[0]['saldo']
                );
            }
        );
    }
}
