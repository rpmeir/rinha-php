<?php

namespace Rinha\Services;

use Rinha\Database\InMemoryContext;
use Rinha\Database\MysqlContext;
use Rinha\Database\SqliteContext;
use Rinha\Entities\Transacao;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Entities\TransacaoDTO;
use Rinha\Entities\UltimaTransacaoDTO;
use Rinha\Repositories\TransacaoRepository;
use Rinha\Services\Interfaces\ITransacaoService;

class TransacaoService implements ITransacaoService
{
    private $repository;

    public function __construct($repository = new TransacaoRepository(new InMemoryContext()))
    {
        $this->repository = $repository;
    }

    /** @return PromiseInterface<?Transacao> **/
    public function create(Conta $conta, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        return $this->repository->addTransaction($conta->id, $transacaoDTO)->then(
            function (?Transacao $transacao) {
                return $transacao;
            }
        );
    }

    public function transacaoValida(Conta $conta, object $data): TransacaoDTO | string
    {
        $transacaoDTO = TransacaoDTO::create($data->valor, $data->tipo, $data->descricao);
        if (is_string($transacaoDTO) && !empty($transacaoDTO)) {
            return $transacaoDTO;
        }

        $sinal = $transacaoDTO->tipo === 'd' ? -1 : 1;
        $valor = $transacaoDTO->valor * $sinal;
        $saldoPrevisto = $conta->getSaldo() + $valor + $conta->limite;
        if($saldoPrevisto < 0) {
            return 'Transação invalida: saldo insuficiente';
        }

        return $transacaoDTO;
    }

    /** @return PromiseInterface<?array<UltimaTransacaoDTO>> **/
    public function getDezUltimasTransacoes(int $contaId): PromiseInterface
    {
        return $this->repository->lastTenTransactions($contaId)->then(
            function (?array $transacoes) { return $transacoes; }
        );
    }
}
