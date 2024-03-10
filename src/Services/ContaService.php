<?php

namespace Rinha\Services;

use Rinha\Database\MysqlContext;
use Rinha\Entities\ConfirmacaoTransacao;
use Rinha\Entities\Conta;
use React\Promise\PromiseInterface;
use Rinha\Repositories\ContaRepository;
use Rinha\Services\Interfaces\IContaService;

class ContaService implements IContaService
{
    private $repository;

    public function __construct($repository = new ContaRepository(new MysqlContext()))
    {
        $this->repository = $repository;
    }

    /** @return PromiseInterface<?Conta> **/
    public function getContaByClienteId(int $clienteId): PromiseInterface
    {
        return $this->repository->findByClienteId($clienteId)->then(
            function (?Conta $conta) { return $conta; }
        );
    }

    /** @return PromiseInterface<?ConfirmacaoTransacao> **/
    public function updateSaldo(Conta $conta, int $valor): PromiseInterface
    {
        return $this->repository->updateSaldo($conta, $valor)->then(
            function (?ConfirmacaoTransacao $confirmacaoTransacao) {
                if ($confirmacaoTransacao === null) {
                    return null;
                }
                return $confirmacaoTransacao;
            }
        );
    }
}
