<?php

namespace Rinha\Repositories;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use Rinha\Entities\Transacao;
use React\Promise\PromiseInterface;
use Rinha\Entities\TransacaoDTO;
use Rinha\Repositories\Interfaces\TransacaoRepositoryInterface;

class TransacaoRepository implements TransacaoRepositoryInterface
{
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /** @return PromiseInterface<?Transacao> **/
    public function add(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        $realizada_em = (new \DateTimeImmutable());
        return $this->db->query(
            'INSERT INTO transacoes (conta_id, valor, tipo, descricao, realizada_em) VALUES (?, ?, ?, ?, ?)',
            [ $conta_id, $transacaoDTO->valor, $transacaoDTO->tipo,
              $transacaoDTO->descricao, $realizada_em->format('Y-m-d H:i:s') ]
            )->then( function (QueryResult $result) use ($conta_id, $transacaoDTO, $realizada_em) {
                if ($result->insertId !== 0) {
                    return new Transacao (
                        $result->insertId,
                        $conta_id,
                        $transacaoDTO->valor,
                        $transacaoDTO->tipo,
                        $transacaoDTO->descricao,
                        $realizada_em
                    );
                }
                return null;
            }
        );
    }
}
