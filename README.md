# CursoFrontEndHCode
 Website construído durante o curso do desenvolvedor web da HCode.

## Dependências utilizadas
- **XAMPP v5.6.15**, com Apache e MySQL.
- **jQuery v1.11.3** (Arquivos na pasta `/lib/jquery`)
- **Bootstrap v3.3.6** (Arquivos na pasta `/lib/bootstrap`)
- **Owl Carousel v1.3.3** (Arquivos na pasta `/lib/owl.carousel`)
- **Plyr v1.3.7** (Arquivos na pasta `/lib/plyr`)
- **Angular v1.5** (Arquivos na pasta `/lib/angularjs`)
- **Raty v2.7** (Arquivos na pasta `/lib/raty`)

## Banco de dados
O script do MySQL para criação das tabelas, *stored procedures* e dados de exemplo está disponível em `/sql/database.sql`.

A criação/execução desse script d banco de dados é necessária para execução das funcionalidades da *webstore*.

Atualmente a conexão está configurada para `localhost`, com o usuário `root` e sem senha. Caso necessite modificar a conexão, modificar o arquivo `/lib/configuration.php`.