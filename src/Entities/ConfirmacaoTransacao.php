<?php

namespace Rinha\Entities;

class ConfirmacaoTransacao
{
    public function __construct(
        public readonly int $limite,
        public readonly int $saldo
    ){

    }
}
