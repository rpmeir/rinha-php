<?php

namespace Rinha\Entities;


class ExtratoDTO
{
    private function __construct(
        public readonly SaldoDTO $saldo,
        private array $ultimas_transacoes = []
    ){

    }

    public function addTransacao(UltimaTransacaoDTO $transacao): void
    {
        $this->ultimas_transacoes[] = $transacao;
    }

    public function getUltimasTransacoes(): array
    {
        return $this->ultimas_transacoes;
    }
}
