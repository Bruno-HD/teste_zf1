# Sobre o projeto

Sistema desenvolvido por Bruno da Costa Monteiro para apresentação das qualidades técnicas


### INSTALAÇÃO:
* Copiar todos os arquivos deste projeto para seu computador.
* Criar um arquivo de docker-compose.yml e executá-lo através do comando ```docker-compose up -d```. Exemplo na área de comandos adicionais.
* Iniciar uma nova DATABASE e uma TABLE no banco de dados Postgres usando os comandos listados abaixo.

### COMANDOS ADICIONAIS: 

Comandos SQL para gerar uma nova base de dados 
```sql
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
```


Exemplo de docker-compose.yml para inicialização da aplicação
```yml
version: '3.7'

services:
  web:
    build:
      context: //teste_zf1
    restart: unless-stopped
    ports:
      - '8089:80'
    volumes:
      - /Users/brunomonteiro/mnt/app/teste_zf1/public:/var/www/html
      - /Users/brunomonteiro/mnt/app/teste_zf1:/var/www
     
    networks:
      - backend
      - frontend

  db:
    image: postgres:11.16
    restart: always
    environment:
      POSTGRES_PASSWORD: example
    networks:
      - backend

  adminer:
    image: adminer
    restart: always
    ports:
      - 8090:8080
    networks:
      - backend
      - frontend

networks:
  backend:
    external: true  
  frontend:
    external: true
    
```


### RECURSOS UTILIZADOS
**PHP 7.4.x**
* Zend_Form
* Zend_Cache
* Zend_Db
* Zend_Validator
* Zend_Filter

**Javascript/HTML**
* Jquery
* Bootstrap
* Library Fans
