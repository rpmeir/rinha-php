<?php

namespace Rinha\Services\Interfaces;

interface IContaService
{
    public function getContaByClienteId(int $clienteId): PromiseInterface;
}
