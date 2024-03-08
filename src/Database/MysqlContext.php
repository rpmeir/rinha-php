<?php

namespace Rinha\Database;

use React\MySQL\Factory;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;
use Rinha\Database\Interfaces\IDatabaseStrategy;
use Rinha\Entities\Conta;
use Rinha\Entities\Transacao;
use Rinha\Entities\TransacaoDTO;
use Rinha\Entities\UltimaTransacaoDTO;

class MysqlContext implements IDatabaseStrategy
{
    private $dbContext;

    public function __construct()
    {
        $credentials = "$_ENV[MYSQL_USER]:$_ENV[MYSQL_PASSWORD]@$_ENV[MYSQL_HOSTNAME]/$_ENV[MYSQL_DATABASE]";
        $this->dbContext = (new Factory())->createLazyConnection($credentials);
    }

    /** @return PromiseInterface<?Conta> **/
    public function findByClienteId(int $clienteId): PromiseInterface
    {
        return $this->dbContext->query(
            'SELECT cliente_id, id, limite, saldo
            FROM contas
            WHERE cliente_id = ?',
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

    /** @return PromiseInterface<?Transacao> **/
    public function addTransaction(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        $realizada_em = (new \DateTimeImmutable());
        return $this->dbContext->query(
            'INSERT INTO transacoes (conta_id, valor, tipo, descricao, realizada_em)
             VALUES (?, ?, ?, ?, ?)',
            [ $conta_id, $transacaoDTO->valor, $transacaoDTO->tipo,
              $transacaoDTO->descricao, $realizada_em->format('Y-m-d H:i:s.u') ]
            )->then( function (QueryResult $result) use ($conta_id, $transacaoDTO, $realizada_em) {
                if ($result->affectedRows !== 1) {
                    return null;
                }
                return new Transacao (
                    $conta_id,
                    $transacaoDTO->valor,
                    $transacaoDTO->tipo,
                    $transacaoDTO->descricao,
                    $realizada_em
                );
            }
        );
    }

    /** @return PromiseInterface<?array<UltimaTransacaoDTO>> **/
    public function lastTenTransactions(int $contaId): PromiseInterface
    {
        return $this->dbContext->query(
            'SELECT conta_id, valor, tipo, descricao, realizada_em
             FROM transacoes
             WHERE conta_id = ?
             ORDER BY realizada_em DESC
             LIMIT 10',
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
