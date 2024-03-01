<?php

namespace Rinha\Repositories;

use Rinha\Database\DatabaseContext;
use Rinha\Database\Interfaces\IDatabaseStrategy;
use Rinha\Entities\Transacao;
use React\Promise\PromiseInterface;
use Rinha\Entities\TransacaoDTO;
use Rinha\Entities\UltimaTransacaoDTO;
use Rinha\Repositories\Interfaces\ITransacaoRepository;

class TransacaoRepository implements ITransacaoRepository
{
    private $db;

    public function __construct(IDatabaseStrategy $db)
    {
        $databaseContext = new DatabaseContext($db);
        $this->db = $databaseContext;
    }

    /** @return PromiseInterface<?Transacao> **/
    public function addTransaction(int $conta_id, TransacaoDTO $transacaoDTO): PromiseInterface
    {
        return $this->db->addTransaction($conta_id, $transacaoDTO)->then(function (?Transacao $transacao) {
            return $transacao;
        });
    }

    /** @return PromiseInterface<?array<UltimaTransacaoDTO>> **/
    public function lastTenTransactions(int $contaId): PromiseInterface
    {
        return $this->db->lastTenTransactions($contaId)->then(function (array $transacoes) {
            return $transacoes;
        });
    }
}
