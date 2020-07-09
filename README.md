# Laravel

- O Laravel é um framework poderoso, livre e de código aberto para o desenvolvimento de aplicações em PHP com a arquitetura MVC (model, view, controller).

- Há duas formas de instalar o Laravel: a primeira é utilizando o composer require e a segunda é criando um projeto Laravel diretamente, que é a forma utilizada aqui.

```
composer create-project prefer-dist laravel/laravel nome-projeto 5.8.*
```

## Artisan

- O Artisan é uma ferramenta de linha de comando do Laravel que nos permite fazer algumas coisas, entre elas subir um servidor de testes.

```
php artisan serve

PHP 7.4.3 Development Server (http://127.0.0.1:8000) started
```

## Rotas

- Para criarmos uma rota iremos na pasta routes e utilizaremos o arquivo web.php.

- Nele já possuimos uma rota do próprio Laravel mas podemos criar a nossa seguindo o exemplo do arquivo.

```
Route::get('/ola', function(){
    echo "Olá mundo";
});
```

- Passamos por parâmetro a nossa URL e dentro da função colocamos o que irá acontecer quando esta URL for acessada, podemos exibir uma mensagem, retornar um arquivo da view, etc.


## Controller

- Os nossos arquivos de controller serão todos criados dentro de app/Http/Controller e todos irão extender a classe Controller.

- No arquivo de rotas vamos definir que ao acessarmos uma URL iremos acessar a controller desta requisição utilizando o nome da controller e o método a ser acessado.

```
Route::get('/series', 'SeriesController@listarSeries');
```

- Caso mudemos o namespace do arquivo de controller precisamos informar no arquivo de rotas o namespace correto do controlador.


## View

- Os arquivos de view serão criados dentro da pasta resources/view. 

- Dentro da controller iremos retornar a view que desejamos que seja exibida com a seguinte sintaxe:

```
return view('series.index', [
    'series' => $series,
]);
```

- Antes do ```.``` informamos a pasta que o arquivo está localizado e depois dele o nome do arquivo.

- Como segundo parâmetro passamos as váriaveis que queremos que a view enxergue, sendo ```nome_variavel => $variavel```.


## Blade

- Blade é uma ferramenta poderosa oferecida pelo Laravel para nos ajudar com o front-end e a repetição de código. Para utilizar o Blade criaremos um arquivo chamado ```layout.blade.php``` na pasta view.

- Blade trabalha com sessões, usaremos o ```@yield('sessao')```` para definir os conteúdos que entrarão naquele local.

- Os arquivos que irão utilizar o arquivo de layout do blade precisam ter a extensão ```blade.php```.

- Para informar ao Laravel que aquele arquivo utiliza o arquivo de layout adicionamos uma marcação no inicio do arquivo.

- Informamos também as sessões e seus respectivos conteúdos.

```
@extends('layout')

@section('cabecalho')
Listar Séries
@endsection

@section('conteudo')
<a href="/series/criar" class="btn btn-dark mb-2">Adicionar Série</a>

<ul class="list-group">
    <?php foreach($series as $serie): ?>
        <li class="list-group-item"><?= $serie; ?></li>   
    <?php endforeach; ?>
</ul>
@endsection
```
- Podemos escrever também códigos substituindo as tags PHP por ```@``` deixando o código mais amigável para quem não tem familiaridade com back-end.

- Com o Blade nós colocamos os códigos repetidos num único arquivo, isto facilita a manutenção e a leitura do código.


## Banco de Dados

- Neste exemplo está sendo utilizado SQLite, o arquivo deve ser criado dentro da pasta database.

- Na pasta config temos o arquivo database.php, é nele que adicionamos as configurações corretas para o Laravel saber como se conectar ao banco de dados.

- Para informar com qual banco se conectar podemos colocar no arquivo database ou então mudar no arquivo .env a configuração de conexão do database.


## Migrations

- As Migrations no Laravel são uma forma de escrevermos código PHP e depois migrá-los para o banco de dados.

- No terminal rodamos o comando ```php artisan make:migration nome_migration``` e um arquivo será gerado em database/migrations com duas funções: up e down.

- A função ```up()``` serve para executar a migration, a função ```down()``` serve para reverter a migration.

- Para criarmos a tabela utilizaremos ```Schema::create()``` passando como parâmetros o nome da tabela e uma função que recebe uma váriavel do tipo Blueprint.

- Dentro dessa função utilizaremos a váriavel ```$table``` para definir as colunas da nossa tabela.

```
public function up()
{
    Schema::create('series', function(Blueprint $table){
        $table->string('nome');
    });
}
```

- Na função down() vamos utilizar o ```Schema::drop('nome_tabela');```.

- Para executarmos as migrations iremos utilizar o comando ```php artisan migrate``` no terminal.


## Model

- Os arquivos de modelo serão criados na pasta app.

- Nas modelos vamos herdar da classe Model que vem do Eloquent ORM, o Eloquent é uma ferramenta que mapeia do mundo orientado objetos para o mundo relacional.

- Para informar que está classe é a que trabalha com a tabela do banco adicionamos um atributo ```protected $table = 'nome-tabela';```. Caso o nome da tabela seja o nome da classe em mínusculo e no plural o Laravel já faz isto por padrão, caso contrário é necessário adicionar o atributo. 


## Inserindo Dados

- Para inserir os dados primeiro precisamos buscá-los. Nas nossas rotas iremos criar uma rota que leve para o método store da nossa controller.

```
Route::post('/series/criar', 'SeriesController@store');
```

- No nosso método store precisamos buscar o dado vindo do post, faremos isso através do request.

- Chamamos o método create da classe e passamos num array associativo os dados que queremos inserir. O Laravel protege que qualquer dado seja passado neste array por isso precisamos adicionar na nossa classe o atributo fillable com os dados que podem ser inseridos.

- Adicionamos os atributos vindos do request neste objeto e salvamos.
```
public function store(Request $request)
{
    $nome = $request->nome;
    Serie::create([
        'nome' => $nome
    ]);
}

class Serie extends Model
{
    protected $fillable = ['nome'];
}
```

- O Laravel protege a aplicação em forms que utilizam o post para evitar ataques de request, para resolvermos isso adicionamos ```@csrf ``` dentro do form.

- No método que exibe os dados utilizamos ```Classe::all()``` para pegar todos os dados do banco de dados.