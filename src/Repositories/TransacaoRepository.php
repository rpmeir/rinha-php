<?php

namespace Rinha\Repositories;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use Rinha\Entities\Transacao;
use React\Promise\PromiseInterface;
use Rinha\Entities\TransacaoDTO;

class TransacaoRepository
{
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /** @return PromiseInterface<?Transacao> **/
    public function add(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        $realizada_em = new \DateTimeImmutable();
        return $this->db->query(
            'INSERT INTO transacoes (conta_id, valor, tipo, descricao, realizada_em) VALUES (?, ?, ?, ?, ?)',
            [$conta_id, $transacaoDTO->valor, $transacaoDTO->tipo, $transacaoDTO->descricao, $realizada_em]
        )->then(
            function (QueryResult $result) {

                if ($result->insertId !== 0) {
                    var_dump('last insert ID', $result->insertId);
                }

                return new Transacao(
                    $result->insertId,
                    1,
                    2,
                    'd',
                    'descricao',
                    new \DateTimeImmutable()
                );
            }
        );
    }
}
