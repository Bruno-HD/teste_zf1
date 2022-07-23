CREATE DATABASE db_entregas;

CREATE TABLE IF NOT EXISTS "tb_entrega" (
  "co_entrega" serial NOT NULL,
  PRIMARY KEY ("co_entrega"),
  "ds_titulo" character(255) NOT NULL,
  "tx_descricao" text NOT NULL,
  "dt_prazo_entrega" character(10) NOT NULL,
  "tp_entrega_concluida" character(1) NOT NULL,
  "st_ativo" character(1) DEFAULT 'S' NOT NULL
);