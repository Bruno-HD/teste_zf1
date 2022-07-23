# Sobre o projeto

Sistema desenvolvido por Bruno da Costa Monteiro para apresentação das qualidades técnicas

**Backend - PHP-Zend**
O sistema foi montado utilizando dois módulos, sendo o primeiro apenas de boas vindas e o segundo de entregas.
Para o módulo de Entregas foi feito um CRUD com 3 actions sendo elas Lista, Manter e Remover
* Listar, nesta etapa utiliza-se o zend_db para consultar no banco de dados todas as entregas com st_ativo
* Manter nesta etapa é verificado o parâmetro de co_entrega, que é como foi chamado o campo chave primário da tabela de entrega. Se houver valor neste campo, ele entra no modo de edição senão é um cadastro.
* Remover, nesta etapa, o sistema faz com que a flag st_ativo fique como N no banco de dados e apartir dai ela não será mais visualizada, nem na listagem e nem na edição.

**Editar e Cadastrar foi mantida na mesma action devido a utilização de validadores e filtros bem como para atender a uma forma de utilização do Zend1 onde trabalhei. Neste formato, não precisamos contruir varias vezes a tela, apenas uma vez e o resto da comunicação é feito através de requisições ajax onde a resposta desta action é um json dependendo de como ela é chamada**

**Todas as validações dos formulários são feitas pelo Zend_Form, o que garante maior segurança da informação. Todos dados deverão obrigatoriamente passar por uma validação independente de onde possa vir**


**Frontend**
A parte do frontend foi montada utilizando HTML5, jQuery e Bootstrap.
* Foi utilizado alguns recursos do jQuery como ajax para manter os dados e validar pelo lado do servidor
* No Bootstrap foi utilizado recursos como grid, buttons e outros para tornar a visualização agradável.
* Conforme solicitado o sistema deverá funcionar também de forma responsiva!


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
