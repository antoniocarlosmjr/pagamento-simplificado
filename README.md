# Pagamento Simplificado

API RESTFul para realizar transações de pagamento.

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
criar os volumes, configurar e conectar as redes:

Logo após, execute `docker-compose exec app php artisan key:generate` para gerar uma chave que será copiada para o arquivo `.env` do projeto do Laravel
para garantir que as sessões do usuário e os dados permaneçam seguros.

Por fim, execute o comando `docker-compose exec app php artisan migrate` para executar as migrações.

Pronto, agora podemos executar a aplicação que poderá ser acessada através do `http://localhost:8008/`.

Obs.: Caso queira visualizar o ambiente de configuração do Docker, [clique aqui](https://github.com/antoniocarlosmjr/ambiente-docker-php).

### Testes

(Em breve)

### Mapeamento Relacional

![Modelagem dos dados](https://github.com/antoniocarlosmjr/pagamento-simplificado/blob/master/docs/diagrama-banco.png?raw=true)

### Documentação dos Endpoints

(Em breve - Swagger)

### Referências

- [Docker](https://docs.docker.com/)
- [Laravel](https:://laravel.com/docs)
- [Github Laravel v.8](https:://laravel.com/docs)
