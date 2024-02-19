<?php

namespace Rinha\Repositories;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use Rinha\Entities\Cliente;
use function React\Async\await;

class ClienteRepository
{
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function find(int $id): ?Cliente
    {
        $result = await($this->db->query(
            'SELECT id, nome FROM cliente WHERE id = ?',
            [$id]
        ));
        assert($result instanceof QueryResult);

        if (count($result->resultRows) === 0) {
            return null;
        }

        return new Cliente(
            $result->resultRows[0]['id'],
            $result->resultRows[0]['nome']
        );
    }
}
