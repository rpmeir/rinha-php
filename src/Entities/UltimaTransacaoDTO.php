<?php

namespace Rinha\Entities;

class UltimaTransacaoDTO
{
    private function __construct(
        public readonly int $valor,
        public readonly string $tipo,
        public readonly string $descricao,
        public readonly \DateTimeImmutable $realizada_em
    ){}
}
