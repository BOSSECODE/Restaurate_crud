## Sistema Restaurante

O Sistema de Pratos é uma aplicação web feita com PHP e MySQL, desenvolvida para cadastrar e gerenciar usuários e pratos.

## 1. Principais funções

- **Cadastro de usuários e pratos;**
- **Listagem, edição e exclusão de pratos;**
- **Pesquisa de pratos por usuário;**
- **Relação entre pratos e usuários;**
- **Validação dos campos;**
- **Uso de Prepared Statements para aumentar a segurança.**

## 1.1 Tecnologia e Banco 

Tecnologias: PHP, MySQL, HTML5, CSS3, XAMPP, Apache, Git e GitHub.

Banco de dados: sistema_pratos, com as tabelas usuarios e pratos. Cada prato é relacionado ao usuário responsável pelo cadastro.

## 1.2 Estrutura principal

conexao.php — conexão com o banco;
index.php — lista os pratos;
cadastrar_usuario.php — cadastro de usuários;
cadastrar_prato.php — cadastro de pratos;
editar_prato.php — edição;
excluir_prato.php — exclusão;
pratos_usuario.php — filtro por usuário;
style.css — estilos;
banco.sql — banco e registros.

Execução: colocar o projeto no htdocs do XAMPP, iniciar Apache e MySQL, importar o banco.sql pelo phpMyAdmin e acessar o projeto pelo navegador.

O projeto também utiliza Git/GitHub para versionamento e foi desenvolvido em dupla para fins acadêmicos.