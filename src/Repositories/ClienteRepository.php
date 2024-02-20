<?php

namespace Rinha\Repositories;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use Rinha\Entities\Cliente;
use React\Promise\PromiseInterface;

class ClienteRepository
{
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /** @return PromiseInterface<?Cliente> **/
    public function find(int $id): PromiseInterface
    {
        return $this->db->query(
            'SELECT id, nome FROM clientes WHERE id = ?',
            [$id]
        )->then(function (QueryResult $result) {
            if (count($result->resultRows) === 0) {
                return null;
            }

            return new Cliente(
                $result->resultRows[0]['id'],
                $result->resultRows[0]['nome']
            );
        });
    }
}
