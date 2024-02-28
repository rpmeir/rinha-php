<?php

namespace Rinha\Repositories;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use Rinha\Entities\Transacao;
use React\Promise\PromiseInterface;
use Rinha\Entities\TransacaoDTO;
use Rinha\Entities\UltimaTransacaoDTO;
use Rinha\Repositories\Interfaces\ITransacaoRepository;

class TransacaoRepository implements ITransacaoRepository
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

    public function lastTenTransactions(int $contaId): PromiseInterface
    {
        return $this->db->query(
            'SELECT conta_id, valor, tipo, descricao, realizada_em
             FROM transacoes
             WHERE conta_id = ?
             ORDER BY realizada_em
             DESC LIMIT 10',
            [ $contaId ]
        )->then( function (QueryResult $result) {
            $transacoes = [];
            if (isset($result->resultRows)) {
                foreach ($result->resultRows as $row) {
                    $realizada_em = new \DateTimeImmutable($row['realizada_em']);
                    $transacoes[] = UltimaTransacaoDTO::create(
                        $row['valor'],
                        $row['tipo'],
                        $row['descricao'],
                        $realizada_em
                    );
                }
            }
            return $transacoes;
        });
    }
}
