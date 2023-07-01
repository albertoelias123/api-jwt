# API JWT com Laravel

Este é um exemplo de API de autenticação JWT (JSON Web Token) construída com Laravel. A API permite registrar usuários, fazer login e acessar rotas protegidas com autenticação JWT.


## Índice

- [API JWT com Laravel](#api-jwt-com-laravel)
  - [Índice](#índice)
  - [Requisitos](#requisitos)
  - [Instalação](#instalação)
  - [API](#api)
    - [Testando a API com o Postman](#testando-a-api-com-o-postman)
    - [Autenticação](#autenticação)
  - [Detalhes técnicos](#detalhes-técnicos)
    - [Containerização com Laravel Sail e Docker](#containerização-com-laravel-sail-e-docker)
    - [Padrão de Commits Conventional Commit](#padrão-de-commits-conventional-commit)
      - [Como funciona o padrão Conventional Commit?](#como-funciona-o-padrão-conventional-commit)
    - [Testes com Pest Framework](#testes-com-pest-framework)
      - [Padrão de Teste do Pest](#padrão-de-teste-do-pest)
      - [Benefícios do Pest](#benefícios-do-pest)
      - [Sobre a relação com o PHPUnit](#sobre-a-relação-com-o-phpunit)
      - [Executando os testes](#executando-os-testes)


## Requisitos

Certifique-se de ter os seguintes requisitos antes de iniciar:

- Docker e Docker Compose instalados

## Instalação

Siga estas etapas para configurar e executar a API:

1. Clone este repositório:

   git clone https://github.com/seu-usuario/api-jwt.git

2. Execute o projeto utilizando o Laravel Sail:

```shell
./vendor/bin/sail up -d
```

Certifique-se de ter o Docker e o Docker Compose instalados no seu sistema.

3. Configure o arquivo .env com as informações do banco de dados e as demais configurações necessárias.

No arquivo .env.example estão algumas atributos para ativar o debug com o Laravel Sail, já esta configurado para utilizar basta descomentar a linha:
```env
SAIL_XDEBUG_MODE=develop,debug,coverage
```

Esse atributo `SAIL_XDEBUG_MODE` permite a utilização do XDEBUG para depuração e análise de cobertura de código.

4. Execute as migrações e seeders do banco de dados:

```shell
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

## API

### Testando a API com o Postman

Você pode usar o [Postman](https://www.postman.com/) para testar a API de forma interativa. Importe a coleção `api-jwt.postman_collection.json` localizada na raiz do projeto e utilize os diferentes endpoints disponíveis para interagir com a API.

### Autenticação

A API utiliza autenticação baseada em JWT para proteger os endpoints. Para acessar as rotas protegidas, você precisa fornecer um token JWT válido no cabeçalho da requisição.

Para obter o token JWT, faça uma requisição POST para `/api/v1/login` enviando as credenciais de um usuário existente no corpo da requisição. O token JWT será retornado na resposta da requisição. Inclua esse token no cabeçalho da requisição para acessar as rotas protegidas, utilizando o formato:


```bash
Authorization: Bearer <token>
```

## Detalhes técnicos

- Restrição de acesso é feita utilizando Policies com regras adicionais e tipos de usuário definidos.
- Foi utilizado o padrão Conventional Commits
- Foi utilizado o Pest para realizar os Testes
- O ambiente de desenvolvimento está totalmente containerizado utilizando o Laravel Sail e Docker

### Containerização com Laravel Sail e Docker

O ambiente de desenvolvimento está totalmente containerizado utilizando o Laravel Sail e Docker. O Laravel Sail é uma maneira simples de configurar um ambiente de desenvolvimento local com Docker para projetos Laravel. Ele fornece um ambiente consistente e isolado para executar o aplicativo Laravel, juntamente com todas as suas dependências. Com o Docker, é possível garantir a portabilidade e a reprodução do ambiente de desenvolvimento em diferentes máquinas.

A utilização do Laravel Sail facilita a configuração do ambiente de desenvolvimento, fornecendo uma estrutura pré-configurada com os serviços necessários, como banco de dados, servidor web e outros. Além disso, a containerização permite isolar o ambiente de desenvolvimento, evitando conflitos entre dependências e facilitando a colaboração em equipe.

### Padrão de Commits Conventional Commit

Neste projeto, foi adotado o padrão de commits Conventional Commit para fornecer uma estrutura consistente e informativa para as mensagens de commit. Esse padrão ajuda a transmitir claramente a intenção de cada commit e facilita a geração de changelogs automatizados.

#### Como funciona o padrão Conventional Commit?

O padrão Conventional Commit segue uma convenção específica para as mensagens de commit, que consiste em três partes principais: tipo, escopo e descrição. Cada parte é separada por dois-pontos (:). Aqui está uma descrição detalhada de cada parte:

1. **Tipo**: Indica a natureza do commit e é representado por palavras-chave específicas. Alguns exemplos comuns de tipos são:

   - **feat**: Novo recurso adicionado ao projeto.
   - **fix**: Correção de bug.
   - **docs**: Alterações na documentação.
   - **chore**: Atualizações relacionadas a tarefas de manutenção.
   - **refactor**: Refatoração de código existente.
   - **test**: Adição ou modificação de testes.

2. **Escopo** (opcional): Refere-se à parte específica do projeto que está sendo afetada pelo commit. Nem todos os commits terão um escopo.

3. **Descrição**: Fornece uma breve descrição do que foi realizado no commit. Deve ser claro e conciso.

Ao seguir o padrão Conventional Commit e utilizar emojis para os tipos de commit, você contribui para um histórico de commits mais consistente e compreensível, facilitando a colaboração e o acompanhamento das mudanças no projeto.

### Testes com Pest Framework

Neste projeto, optei por utilizar o framework Pest para realizar os testes. Mas afinal, por que utilizei o Pest?

#### Padrão de Teste do Pest

Primeiramente, é importante destacar que o Pest segue um padrão de teste diferente do PHPUnit, que é o framework de teste mais comumente utilizado. Enquanto o PHPUnit utiliza uma estrutura baseada em classes e métodos para escrever testes, o Pest adota uma abordagem mais descritiva, utilizando funções auxiliares como `test`, `it`, `expect`, entre outras. Essa abordagem torna os testes mais legíveis e expressivos, seguindo o conceito de "teste como especificação".

#### Benefícios do Pest

Agora, vamos aos benefícios que me levaram a escolher o Pest como framework de teste para este projeto:

- **Sintaxe expressiva e legível**: A sintaxe fluente e descritiva do Pest facilita a leitura e compreensão dos testes, tornando-os mais claros e intuitivos para mim e para outros membros da equipe.

- **Execução paralela de testes**: O Pest permite a execução de testes em paralelo, o que é especialmente útil em projetos maiores, onde o tempo de execução dos testes pode ser significativo. Com a execução paralela, ganho uma melhoria no desempenho e consigo obter resultados mais rapidamente.

- **Análise de cobertura de código**: O Pest integra-se facilmente com ferramentas de análise de cobertura de código, o que me permite identificar áreas do código que não estão sendo testadas adequadamente. Isso contribui para melhorar a qualidade dos testes e garantir uma cobertura abrangente.

- **Modo de observação (watch mode)**: O Pest possui um recurso de modo de observação que monitora automaticamente as alterações nos arquivos de teste. Isso significa que, durante o desenvolvimento, posso realizar alterações no código e os testes relevantes serão executados automaticamente, proporcionando um fluxo de trabalho mais ágil e eficiente.

- **Testes de arquitetura (ainda não utilizado neste projeto)**: Embora não tenha sido aplicado neste projeto em particular, o Pest oferece recursos para realizar testes de arquitetura, o que é útil para verificar se as dependências e estruturas do projeto estão configuradas corretamente. Essa funcionalidade pode ser explorada em futuros projetos, caso necessário.

#### Sobre a relação com o PHPUnit

É importante mencionar que o Pest é construído em cima do PHPUnit, que é um framework de teste amplamente utilizado e confiável. O Pest utiliza o PHPUnit como base, fornecendo uma camada adicional de abstração e recursos específicos para testes mais descritivos. Portanto, posso aproveitar a robustez e a confiabilidade do PHPUnit, ao mesmo tempo em que utilizo a sintaxe expressiva e os recursos adicionais do Pest.

#### Executando os testes

Para executar os testes utilizando o Pest neste projeto, você pode seguir as seguintes etapas:

1. Inicie o ambiente de desenvolvimento utilizando o Laravel Sail:

```shell
./vendor/bin/sail up -d
```

Certifique-se de ter as dependências do projeto instaladas corretamente antes de executar os testes.

2. Execute os testes utilizando o comando a seguir:

```shell
./vendor/bin/sail test
``` 

Isso irá executar todos os testes definidos no projeto.

Além disso, o Pest oferece recursos avançados que podem ser úteis durante a execução dos testes. Aqui estão alguns exemplos:

- **Execução paralela dos testes**: Para executar os testes em paralelo e acelerar ainda mais o processo, utilize o seguinte comando:

```shell
./vendor/bin/sail test --parallel
```

- **Análise de tempo de execução (Profiling)**: Para identificar os testes mais lentos e otimizar o projeto, você pode executar a análise de tempo de execução. Utilize o seguinte comando:

```shell
./vendor/bin/sail test --profie
```

- **Análise de cobertura de testes**: O Pest também oferece a possibilidade de realizar uma análise de cobertura de testes. Para isso, execute o seguinte comando:

```shell
./vendor/bin/sail test --coverage
```

Essas são algumas opções que o Pest oferece para auxiliar na execução e análise dos testes. Aproveite esses recursos para garantir a qualidade e confiabilidade do seu código.
