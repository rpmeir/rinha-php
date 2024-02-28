<?php

namespace Rinha\Entities;

class Transacao
{
    public function __construct(
        public readonly int $conta_id,
        public readonly int $valor,
        public readonly string $tipo,
        public readonly string $descricao,
        public readonly \DateTimeImmutable $realizada_em
    ){

    }
}
