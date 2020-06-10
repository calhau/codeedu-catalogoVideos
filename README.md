## Aluno Bruno Leal
Versao 1;

## Descrição
Microsserviço de catálogo

Desafio 1  
[x] - Criar Recurso de Category  
[x] - Criar Recurso de Genre

Foram criados as respectivas Factory,Model,Controller,Migration,Seed.

## Rodar a aplicação

Estamos usando o docker, logo basta entrar no diretorio raiz e executar  
docker-compose up -d

#### Para Windows
(Só funciona no Git Bash ou similar)
```bash
dos2unix.exe .docker\entrypoint.sh
```
Isto irá converter os caracteres de final de linha e final de arquivo para unix style.

#### Accessar Aplicação
```
http://localhost:8000
```

### Infos Bruno

Para rodar testes => vendor/bin/phpunit  
Para rodar testes => vendor/bin/phpunit --filter NomeClass (apenas a classe sera testada)  
Para rodar testes => vendor/bin/phpunit --filter NomeClass::testExample (apenas o metodo sera testada)  
Para criar testes => php artisan make:test CategoryTest --unit  
*lembrar que o prefixo dos metodos devem ser "test"* 


Testar  

- vendor/bin/phpunit --filter CategoryTest::testIfUseTraits
- vendor/bin/phpunit --filter BasicCrudController::testIfFindOrFailFetchModel
