<?php

namespace Rinha\Entities;

class UltimaTransacaoDTO
{
    private function __construct(
        public readonly int $valor,
        public readonly string $tipo,
        public readonly string $descricao,
        public readonly string $realizada_em
    ){}

    public static function create(int $valor, string $tipo, string $descricao, \DateTimeImmutable $realizada_em): self
    {
        return new self(
            $valor,
            $tipo,
            $descricao,
            $realizada_em->format('Y-m-d H:i:s')
        );
    }
}
