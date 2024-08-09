## VacationPlansAPI


## Contexto:
Este projeto trata-se de uma simples API Restful onde o cliente pode fazer cadastro, edição, listagem, deleção e também gerar um PDF 
para download dos planos de férias. Além disso também é preciso realizar login para ter a autenticação necessária para relizar as
requests.


## Desenvolvimento:

- Backend
    Feito em PHP v8.1 com o framework Laravel v10.10.

- Banco de dados

    Implementado com o banco de dados relacional MySQL. O banco contará com as tabelas de vacations e users.

- Testes unitários:
   PHPunit.

- Documentação:
   Swagger.

## Instalando dependências:

- Backend
    Você precisará ter PHP 8.1 instalado e também o Laravel 10.10 e também o composer para instalações de dependências. 
    Após clonar este repositório em seu diretório local, acesse a pasta onde foi clonado, 
    então rode o comando `composer install` para instalar todas as dependências. Crie um arquivo
    .env contendo suas credencias para acesso ao banco de dados e servidor. Um Exemplo do que
    precisará em seu arquivo está no arquivo '.env.example', crie seu próprio arquivo a partir dele.

- Banco de dados
    Após instalar back-end você irá configurar seu banco de dados. Caso já
    tenha o serviço rodando use as credencias no arquivo .env, caso não
    tenha instalado poderá rodar também via container Docker.
    Ex: `docker run --name my-db -e MYSQL_PASSWORD=mysecretpassword -d mysql`.

## Executando aplicação:

  - Para implementar as tabelas do banco de dados:
      Acesse a pasta raíz do projeto e rode  `php artisan migrate`
      para rodar as migrations e também rode  `php artisan db:seed` que geram o user no qual você
      irá testar os endpoints e também as migrations do banco
     
- Iniciando o servidor Laravel:
      Rode `php artisan serve` e pronto, só acessar via 'http://127.0.0.1:8000/'.

## Testes e documentação:

 - Testes:
     Este projeto conta com cases de testes unitários de todos os endpoints, para rodá-los: `php artisan test`.

 - Documentação:
     Para acessar toda a documentação da API com exemplos de requests, responses e tudo mais: `php artisan l5-swagger:generate`
     e então acesse 'http://127.0.0.1:8000/api/documentation' para acessar os exemplos em seu navegador.

Obs: 
    Também poderá executar os tests via clients como Insomnia se prefirir. O usuário e senha para o login são: 'silvio@meuemail.com' e 'password'
    respectivamente.
