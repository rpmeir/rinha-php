<?php

namespace Rinha\Services\Interfaces;
use React\Promise\PromiseInterface;

interface IContaService
{
    public function getContaByClienteId(int $clienteId): PromiseInterface;
}
