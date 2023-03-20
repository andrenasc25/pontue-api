# API Pontue
<p align="center">Uma api para Criar, Ler, Atualizar e Deletar produtos</p>

### 🎲 Rodando o Back End

```bash
# Clone este repositório
$ git clone <https://github.com/andre-rep/pontue-api>

# Acesse a pasta do projeto no terminal/cmd
$ cd pontue-api

# Instale as dependências
$ composer install

# Executar as migrações
$ php artisan migrate

# Executar os seeds
$ php artisan db:seed --class=UserTableSeeder

# Execute o servidor laravel
$ php artisan serve

# O servidor inciará na porta:8000 - acesse <http://localhost:8000>
```

### 🛠 Utilizar o Postman

Para utilizar o postman, basta importar a collection que já foi preparada e está na raíz do projeto:

- Pontue Api.postman_collection.json

## Verbos Http da API
| Método | Endpoint | Descrição | Exemplo de valores a serem enviados |
|---|---|---|---|
| `POST` | http://localhost:8000/api/login | Realiza o login na API. Utilizar as credenciais de exemplo. Após receber o token como resposta é necessário colocar no header com a chave "Authorization" e o valor "Bearer " + token para funcionar nas requisições seguintes | email: email@exemplo.com e password: password |
| `POST` | http://localhost:8000/api/v1/produto/ | Insere novos produtos, aceita pelo menos dois produtos por inserção. Separar cada produto no valor por um ponto-e-vírgula (;) | nome: produto;produto2 e descricao: descrição do produto;descrição do produto2|
| `POST` | http://localhost:8000/api/v1/logout | Faz logout na API | Apenas é necessário clicar para fazer a requisição |
| `GET` | http://localhost:8000/api/v1/produto?filtro=1,2 | Exibe produtos através de seus id's como parâmetro, pelo menos dois são necessários para fazer a requisição | É necessário apenas enviar a url |
| `POST` | http://localhost:8000/api/v1/produto/1,2,3 | Atualiza produtos de acordo com parâmetros e valores passados | _method: put, nome: nome atualizado;nome atualizado2;nome atualizado3 e descrição atualizada;descrição atualizada2; descrição atualizada3 |
| `DELETE` | http://localhost:8000/api/v1/produto/3,4 | Deleta pelo menos dois produtos enviados como id pela url | Não é necessário enviar valores no corpo da requisição |
| `POST` | http://localhost:8000/api/v1/reset | Reseta a senha do usuário | newPassword: password2 |


### Autor
---

<a href="https://github.com/andre-rep">
 <img style="border-radius:50px;" src="https://avatars.githubusercontent.com/u/36203075?v=4" width="100px;" alt=""/>
 <br />
 <sub><b>André Nascimento</b></sub></a> <a href="https://github.com/andre-rep" title="Github">🚀</a>


Feito com ❤️ por André Nascimento