# Model Diagram

## Diagrama

```mermaid
classDiagram
  direction LR
  class Conta {
    -id : id
    -limite : int
    -saldo : int
  }
  class Cliente{
    -id : int
    -nome : string
  }
  class Transacao{
    -id : int
    -valor : int
    -tipo : char
    -descricao : string
    -reailzada_em : DateTime
  }
  Conta "*" o-- "1" Cliente
  Conta "1" --o "*" Transacao
```

## Entrypoint SQLs

```sql

create table clientes (
   id int not null auto_increment,
   nome varchar(100) not null,
   primary key (id)
);


create table contas (
   id int not null auto_increment,
   cliente_id int not null,
   limite bigint not null,
   saldo bigint not null,
   primary key (id)
);

alter table contas add foreign key (cliente_id) references clientes(id);

create index idx_conta on contas (cliente_id, id, limite, saldo);


create table transacoes (
   id int not null auto_increment,
   conta_id int not null,
   valor bigint not null,
   tipo char(1) not null,
   descricao varchar(20) not null,
   realizada_em datetime not null, -- DEFAULT CURRENT_TIMESTAMP,
   primary key (id)
);

alter table transacoes add foreign key (conta_id) references contas(id);

create index idx_transacao on transacoes (conta_id, valor, tipo, descricao, realizada_em);

CREATE TRIGGER ins_transacao AFTER INSERT ON transacoes
FOR EACH ROW
BEGIN
  UPDATE contas
  SET contas.saldo = contas.saldo + (NEW.valor * (CASE WHEN NEW.tipo = 'd' THEN -1 ELSE 1 END))
  WHERE contas.id = NEW.conta_id;
END;

insert into clientes (nome)
values
('Milionario'),
('Jose Rico'),
('Dinheirudo'),
('Pobrecito'),
('Gastao');

insert into contas (cliente_id, limite, saldo)
values
(1,   100000, 0),
(2,    80000, 0),
(3,  1000000, 0),
(4, 10000000, 0),
(5,   500000, 0);


```
