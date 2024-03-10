<?php

namespace Rinha\Database;

use React\Promise\PromiseInterface;
use Rinha\Database\Interfaces\IDatabaseStrategy;
use Rinha\Entities\ConfirmacaoTransacao;
use Rinha\Entities\TransacaoDTO;
use Rinha\Entities\UltimaTransacaoDTO;
use Rinha\Entities\Conta;
use Rinha\Entities\Transacao;

class DatabaseContext
{
    private $db;

    public function __construct( IDatabaseStrategy $db )
    {
        $this->db = $db;
    }

    public function setDatabase(IDatabaseStrategy $db): void
    {
        $this->db = $db;
    }

    /** @return PromiseInterface<?Conta> **/
    public function findByClienteId(int $id): PromiseInterface
    {
        return $this->db->findByClienteId($id)->then(function(?Conta $conta) {
            return $conta;
        });
    }

    /** @return PromiseInterface<?Transacao> **/
    public function addTransaction(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        return $this->db->addTransaction($conta_id, $transacaoDTO)->then(function(?Transacao $transacao) {
            return $transacao;
        });
    }

    /** @return PromiseInterface<?array<UltimaTransacaoDTO>> **/
    public function lastTenTransactions(int $contaId): PromiseInterface
    {
        return $this->db->lastTenTransactions($contaId)->then(function(array $transacoes) {
            return $transacoes;
        });
    }

    /** @return PromiseInterface<?ConfirmacaoTransacao> **/
    public function updateSaldo(Conta $conta, int $valor): PromiseInterface
    {
        return $this->db->updateSaldo($conta, $valor)->then(function(?ConfirmacaoTransacao $confirmacaoTransacao) {
            return $confirmacaoTransacao;
        });
    }
}
