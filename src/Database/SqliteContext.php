<?php

namespace Rinha\Database;

use Clue\React\SQLite\Factory;
use Clue\React\SQLite\Result;
use React\Promise\PromiseInterface;
use Rinha\Database\Interfaces\IDatabaseStrategy;
use Rinha\Entities\Conta;
use Rinha\Entities\Transacao;
use Rinha\Entities\TransacaoDTO;
use Rinha\Entities\UltimaTransacaoDTO;

class SqliteContext implements IDatabaseStrategy
{
    private $dbContext;

    public function __construct()
    {
        $filename = __DIR__ . '/rinha.db';
        $this->dbContext = (new Factory())->openLazy($filename);
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
            function (Result $result) {
                if (count($result->rows) === 0) {
                    return null;
                }

                return new Conta(
                    $result->rows[0]['id'],
                    $result->rows[0]['cliente_id'],
                    $result->rows[0]['limite'],
                    $result->rows[0]['saldo']
                );
            }, function (\Exception $error) {
                // the query was not executed successfully
                echo 'Error get conta: ' . $error->getMessage() . PHP_EOL;
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
            )->then( function (Result $result) use ($conta_id, $transacaoDTO, $realizada_em) {
                if ($result->changed === 0) {
                    return null;
                }
                return new Transacao (
                    $conta_id,
                    $transacaoDTO->valor,
                    $transacaoDTO->tipo,
                    $transacaoDTO->descricao,
                    $realizada_em
                );
            }, function (\Exception $error) {
                // the query was not executed successfully
                echo 'Error on add transaction: ' . $error->getMessage() . PHP_EOL;
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
        )->then( function (Result $result) {
            $transacoes = [];
            if (isset($result->rows)) {
                foreach ($result->rows as $row) {
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
        }, function (\Exception $error) {
            // the query was not executed successfully
            echo 'Error on get last 10 transactions: ' . $error->getMessage() . PHP_EOL;
        });
    }

}
