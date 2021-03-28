# Pagamento Simplificado

API RESTFul para realizar transações de pagamento entre usuários comuns e lojistas.

#### Tecnologias Utilizadas

- PHP 7.4
- Laravel 8
- Docker
- MySQL 5.7

### Configuração e Instalação do Projeto

A primeira etapa a ser feita é renomear o arquivo `.env.example` no diretório `/src` para `.env` que já possui as configurações padrões
a serem adicionadas no Laravel.

A seguir, vá para dentro do diretório `/src` e execute o comando `composer install` para buildar a aplicação. Para isso é
necessário que possua o Composer instalado em sua máquina.

Em seguinda, é necessário saber que a configuração deste projeto foi criada utilizando o Docker, para isso é necessário 
têlo instalado. Após a instalação, abra o diretório e execute `docker-compose up -d` para iniciar todos os contêineres,
criar os volumes, configurar e conectar as redes.

Logo após, execute `docker-compose exec app php artisan key:generate` para gerar uma chave que será copiada para o arquivo `.env` do projeto do Laravel
para garantir que as sessões do usuário e os dados permaneçam seguros.

Por fim, execute o comando `docker-compose exec app php artisan migrate` para executar as migrações.

Pronto, agora podemos executar a aplicação que poderá ser acessada através do `http://localhost:8008/`.

Obs.: Caso queira visualizar o ambiente de configuração do Docker, [clique aqui](https://github.com/antoniocarlosmjr/ambiente-docker-php).

### Testes

Para a execução dos testes dessa aplicação sera necessário estar dentro do diretório `/src` e com o Docker em execução utilize o seguinte comando:

`docker-compose exec app php artisan test`

O resultado esperado deve ser o exibido abaixo:

![Execucao_dos_testes](https://github.com/antoniocarlosmjr/pagamento-simplificado/blob/master/docs/execucao-testes.png?raw=true)

### Mapeamento Relacional

A estrutura do banco de dados dessa aplicação foi organizada com a criação de três tabelas: usuários, carteiras e transações. Os usuários possuem carteiras e realizam transferências que são armazenadas nas transações. A seguir, pode ser visto um diagrama que representa tal estrutura.

![Modelagem dos dados](https://github.com/antoniocarlosmjr/pagamento-simplificado/blob/master/docs/diagrama-banco.png?raw=true)

### Documentação dos Endpoints

[Clique aqui para acessar a documentação da API](https://app.swaggerhub.com/apis-docs/carlos12antoni/PagamentoSimplificado/1.0.0)

### Motivos de escolha das tecnologias

Foi escolhido o framework Laravel pelo fato de existir uma vasta quantidade de funcionalidades já prontas para uso
em APIs Restfull, além disso deixa o projeto bem estruturado em relação ao MVC. Também foi pensado no Slim, apesar de já
ter usado, mas para o desafio ser maior foi escolhido o Laravel.

A estrutura foi feita em Docker pelo fato de ser reutilizável em outros projetos e ser o mais escalável possível.
Por conta da virtualização por container, ele também possibilita um ambiente leve e isolado para rodar a aplicação. 

### Pontos de Melhorias

- Criação de um versionamento da API, pois por enquanto não possui;
- Uso do padrão de projeto Strategy para definir as estratégias de autorizações na realização das transferências, dado que por enquanto só possui
um tipo acessando um serviço externo;
- Utilizar algum serviço de tratamento de filas para tratar as realizações de transferências. Serviços estes como o Redis, RabbitMQ ou outro;
- Utilizar uma ferramenta de integração contínua (CI) para realizar os testes antes de buildar a aplicação;

### Referências e Documentações utilizadas

- [Docker](https://docs.docker.com/)
- [Laravel](https://laravel.com/docs/8.x)
- [Github Laravel v.8](https://github.com/laravel/laravel)
- [JWT](https://jwt.io/)
- [JWT-Laravel](https://jwt-auth.readthedocs.io/en/develop/)
