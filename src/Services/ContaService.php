<?php

namespace Rinha\Services;

use Rinha\Entities\Conta;
use React\Promise\PromiseInterface;
use Rinha\Repositories\ContaRepository;
use Rinha\Services\Interfaces\IContaService;

class ContaService implements IContaService
{
    private $repository;

    public function __construct(ContaRepository $repository)
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
}
