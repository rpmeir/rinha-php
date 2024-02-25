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

    public static function create($valor, $tipo, $descricao): TransacaoDTO | string
    {
        $erros = TransacaoDTO::validate($valor, $tipo, $descricao);
        if(!empty($erros)) {
            return $erros;
        }
        return new TransacaoDTO($valor, $tipo, $descricao);
    }

    public static function validate($valor, $tipo, $descricao): string
    {
        $erros = '';

        $nenhumCampoVazio =  !empty($valor) && !empty($tipo) && !empty($descricao);
        $erros .= $nenhumCampoVazio ? '' : "Todos os campos devem ser preenchidos.\n";

        $valorInteiroMaiorQueZero = is_int($valor) && $valor > 0;
        $erros .= $valorInteiroMaiorQueZero ? '' : "O valor deve ser um inteiro positivo.\n";

        $tipoCorreto = in_array($tipo, ['c', 'd'], true);
        $erros .= $tipoCorreto ? '' : "O tipo deve ser C ou D.\n";

        $descricaoValida = is_string($descricao) && strlen($descricao) >= 1 && strlen($descricao) <= 10;
        $erros .= $descricaoValida ? '' : "A descricão deve ter entre 1 e 10 caracteres.\n";

        return $erros;
    }
}
