<?php

namespace Rinha\Repositories\Interfaces;

use React\Promise\PromiseInterface;
use Rinha\Entities\TransacaoDTO;

interface TransacaoRepositoryInterface
{
    public function add(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface;
}
