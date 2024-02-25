<?php

namespace Rinha\Services\Interfaces;

use React\Promise\PromiseInterface;
use Rinha\Entities\Conta;
use Rinha\Entities\TransacaoDTO;

interface TransacaoServiceInterface
{
    public function create(Conta $conta, TransacaoDTO $transacaoDTO): PromiseInterface;
    public function transacaoValida(Conta $conta, object $data): TransacaoDTO | string;
}
