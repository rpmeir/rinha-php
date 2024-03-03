<?php

namespace Rinha\Database;
use React\Promise\Deferred;
use React\Promise\Promise;
use Rinha\Database\Interfaces\IDatabaseStrategy;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Entities\Transacao;
use Rinha\Entities\TransacaoDTO;
use Rinha\Entities\UltimaTransacaoDTO;

class InMemoryContext implements IDatabaseStrategy
{
    private array $db;

    public function __construct()
    {
        $this->db['contas'] = [
            new Conta(1, 1, 100000, 0),
            new Conta(2, 2, 80000, 0),
            new Conta(3, 3, 1000000, 0),
            new Conta(4, 4, 10000000, 0),
            new Conta(5, 5, 500000, 0)
        ];

        $this->db['transacoes'] = [];
    }

    public function findByClienteId(int $id): PromiseInterface
    {
        $deferred = new Deferred();
        $promise = $deferred->promise();
        $deferred->resolve($id);

        return $promise->then(
            function ($id) {
                $filteredContas = array_filter(
                    $this->db['contas'],
                    function ($conta) use ($id) {
                    return $conta->cliente_id === $id;
                });

                return array_shift($filteredContas);
            }
        );
    }

    public function addTransaction(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        $deferred = new Deferred();
        $promise = $deferred->promise();
        $deferred->resolve(['conta_id' => $conta_id, 'transacaoDTO' => $transacaoDTO]);

        return $promise->then(
            function (array $data) {
                $realizada_em = new \DateTimeImmutable();
                $transacao =  new Transacao (
                    $data['conta_id'],
                    $data['transacaoDTO']->valor,
                    $data['transacaoDTO']->tipo,
                    $data['transacaoDTO']->descricao,
                    $realizada_em
                );
                $this->db['transacoes'][] = $transacao;

                array_walk($this->db['contas'], function (&$object) use ($data) {
                    if ($object->id === $data['conta_id']) {
                        $saldoAtual = $object->getSaldo();
                        $valorTransacao = $data['transacaoDTO']->valor * ($data['transacaoDTO']->tipo === 'd' ? -1 : 1);
                        $object->setSaldo($saldoAtual + $valorTransacao);
                    }
                });

                return $transacao;
            }
        );
    }

    public function lastTenTransactions(int $contaId): PromiseInterface
    {
        $transacoes = array_filter(
            $this->db['transacoes'],
            function ($transacao)use ($contaId) {
            return $transacao->conta_id === $contaId;
        });

        return array_shift($transacoes);
    }
}
