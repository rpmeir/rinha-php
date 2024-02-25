<?php

namespace Rinha\Services;

use Rinha\Entities\Transacao;
use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Entities\TransacaoDTO;
use Rinha\Repositories\TransacaoRepository;
use Rinha\Services\Interfaces\TransacaoServiceInterface;

class TransacaoService implements TransacaoServiceInterface
{
    private $repository;

    public function __construct(TransacaoRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return PromiseInterface<?Transacao> **/
    public function create(Conta $conta, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        return $this->repository->add($conta->id, $transacaoDTO)->then(
            function (?Transacao $transacao) {
                return $transacao;
            }
        );
    }

    public function transacaoValida(Conta $conta, object $data): TransacaoDTO | string
    {
        $transacaoDTO = TransacaoDTO::create($data->valor, $data->tipo, $data->descricao);
        if ($transacaoDTO === null) {
            return "Entidade incorreta";
        }

        $sinal = $transacaoDTO->tipo === 'd' ? -1 : 1;
        $valor = $transacaoDTO->valor * $sinal;
        $saldoPrevisto = $conta->getSaldo() + $valor + $conta->limite;
        if($saldoPrevisto < 0) {
            return 'Transação invalida: saldo insuficiente';
        }

        return $transacaoDTO;
    }
}
