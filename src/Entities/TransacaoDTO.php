<?php

namespace Rinha\Entities;

class TransacaoDTO
{
    private function __construct(
        public readonly int $valor,
        public readonly string $tipo,
        public readonly string $descricao
    ){

    }

    public static function create($valor, $tipo, $descricao): TransacaoDTO | null
    {
        if(!TransacaoDTO::validate($valor, $tipo, $descricao)) {
            return null;
        }
        return new TransacaoDTO($valor, $tipo, $descricao);
    }

    public static function validate($valor, $tipo, $descricao): bool
    {
        $nenhumCampoVazio =  !empty($valor) && !empty($tipo) && !empty($descricao);
        $valorInteiroMaiorQueZero = is_int($valor) && $valor > 0;
        $tipoCorreto = in_array($tipo, ['c', 'd'], true);
        $descricaoValida = is_string($descricao) && strlen($descricao) >= 1 && strlen($descricao) <= 10;
        return $nenhumCampoVazio && $valorInteiroMaiorQueZero && $tipoCorreto && $descricaoValida;
    }
}
