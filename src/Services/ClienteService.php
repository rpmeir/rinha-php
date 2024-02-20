<?php

namespace Rinha\Services;

use Rinha\Entities\Cliente;
use Rinha\Repositories\ClienteRepository;
use React\Promise\PromiseInterface;

class ClienteService
{
    private $repository;

    public function __construct(ClienteRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @return PromiseInterface<?Cliente> **/
    public function getCliente(int $id): PromiseInterface
    {
        return $this->repository->find($id)->then(function (?Cliente $cliente) {
            return $cliente;
        });
    }
}
