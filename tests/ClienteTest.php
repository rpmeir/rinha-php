<?php

// POST /clientes/[id]/transacoes


test('example', function () {
    expect(true)->toBeTrue();
});

// que receba no body valor, tipo e descricao
// [id] (na URL) deve ser um número inteiro representando a identificação do cliente.
// valor deve um número inteiro positivo que representa centavos. Por exemplo, R$ 10 são 1000 centavos.
// tipo deve ser apenas c para crédito ou d para débito.
// descricao deve ser uma string de 1 a 10 caractéres.
// os tres campos sao obrigatorios

// resposta deve ser { "limite" : 100000, "saldo" : 100000 }
// limite deve ser o limite cadastrado do cliente.
// saldo deve ser o novo saldo após a conclusão da transação.

// Obrigatoriamente, o http status code de requisições para transações bem sucedidas deve ser 200!
// Uma transação de débito nunca pode deixar o saldo do cliente menor que seu limite disponível

//Se uma requisição for deixar o saldo inconsistente, deve retornar HTTP Status Code 422 sem completar a transação!

// Se o atributo [id] da URL for de uma identificação não existente de cliente, a API deve retornar HTTP Status Code 404


// GET /clientes/[id]/extrato
// [id] (na URL) deve ser um número inteiro representando a identificação do cliente.

// Se o atributo [id] for inexistente, a API deve retornar HTTP Status Code 404.

// resposta deve ser HTTP Status Code 200
// {
//     "saldo": {
//         "total": -9098,
//         "data_extrato": "2024-01-17T02:34:41.217753Z",
//         "limite": 100000
//     },
//     "ultimas_transacoes": [
//         {
//             "valor": 10,
//             "tipo": "c",
//             "descricao": "descricao",
//             "realizada_em": "2024-01-17T02:34:38.543030Z"
//         }
//     ]
// }

// saldo
//     total deve ser o saldo total atual do cliente (não apenas das últimas transações seguintes exibidas).
//     data_extrato deve ser a data/hora da consulta do extrato.
//     limite deve ser o limite cadastrado do cliente.
// ultimas_transacoes é uma lista ordenada por data/hora das transações de forma decrescente contendo até as 10
// últimas transações com o seguinte:
//     valor deve ser o valor da transação.
//     tipo deve ser c para crédito e d para débito.
//     descricao deve ser a descrição informada durante a transação.
//     realizada_em deve ser a data/hora da realização da transação.
