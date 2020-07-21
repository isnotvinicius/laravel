# Laravel

- O Laravel é um framework poderoso, livre e de código aberto para o desenvolvimento de aplicações em PHP com a arquitetura MVC (model, view, controller).

- Há duas formas de instalar o Laravel: a primeira é utilizando o composer require e a segunda é criando um projeto Laravel diretamente, que é a forma utilizada aqui.

```
composer create-project --prefer-dist laravel/laravel nome-projeto 5.8.*
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


## Sessão

- Nós conseguimos manipular a sessão no Laravel através do método session do request.

- Podemos adicionar dados na sessão através do ```put()``` informando a chave do valor e o valor.

- Pegamos este dado através do método get e podemos enviar ele no redirecionamento da view por exemplo.

```
$request->session()->put('mensagem', "Série criada com sucesso!");

$request->session()->get('mensagem');
```

- Com o ```put()``` o dado fica na sessão permanentemente o que pode não ser uma solução viável para mensagens, o Laravel nos fornece o método ```flash()``` que deixa o dado da sessão por apenas uma requisição, quando a página é atualizada o dado some da sessão.


## Exclusão de dados

- Dentro da nossa lista de séries iremos adicionar um form com method post e action /series/remover. Poderiamos adicionar um link mas isso faria com que um possível crawler deletasse todos os dados da lista, o form com method post evita isto.

- Adicionamos a rota para exclusão informando que na url iremos receber o id da série em questão como parâmetro.

```
Route::post('/series/remover/{id}', 'SeriesController@destroy');
```

- Podemos adicionar o método delete na rota e adicionar a anotação @method('DELETE') no nosso HTML pois o HTML só suporta os verbos GET e POST, adicionando esta anotação o Laravel envia uma informação a mais na requisição dizendo que este post mapeia para a rota que está com delete.

- Para excluir de fato usaremos no método ```destroy()``` a seguinte sintaxe:

```
Serie::destroy($request->id);
```

## Nomeando Rotas

- Podemos nomear um rota no nosso arquivos de rotas para evitar que sempre precisemos lembrar a rota, basta termos o nome dela.

```
Route::get('/criar/serie', 'SeriesController@create')->name('criar-serie');
```

- Com isso sempre que tivermos que usar a url /criar/serie iremos utilizar criar-serie, isto ajuda quando temos urls muito grandes, nomeando elas de forma simples e curta é mais fácil de lembrar.

- Quando for utilizar o link para esta rota basta adicionar ```{{route('nome-rota')}}```

- Se a url na rota for mudada isto não impacta a aplicação desde que estejamos utilizando o nome da rota.


## Validação de Dados

- O Laravel fornece um método para validação chamado ```validate()``` e nele passamos um array associativo onde a chave é o campo a ser validado e o valor são as regras de validação separadas por um ```|``` (pipe). Para saber os tipos de regras de validação basta checar a documentação do Laravel (<a>https://laravel.com/docs/7.x/validation</a>).

```
$request->validate([
    'nome' => 'required',
]);
```

- Na documentação podemos encontrar um código pronto para exibir essas mensagens de validação para o usuário.

```
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li> {{$error}} </li>
            @endforeach
        </ul>
    </div>
@endif
```

- É interessante extrairmos está validação para um FormRequest. Para criar usaremos o comando:

```
php artisan make:request NomeRequest
```

- O arquivo será criado em http/requests e lá podemos colocar nossa validação no método já criado por ele e definir se qualquer usuário pode utilizar esta validação ou não.

```
class SeriesFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|min:2'
        ];
    }
}
```

- Com isso, ao invés de recebermos um request no método de inserção receberemos a request que nós mesmos criamos e colocamos as regras de validação.

- Podemos também adicionar o método message retornando um array associativo onde a chave é a regra de validação e o valor a mensagem que será exibida.

```
public function messages()
{
    return [
        'nome.required' => 'O campo nome é obrigatório',
        'nome.min' => 'O campo nome deve conter no mínimo 2 caracteres'
    ];
}
```

- Com isso podemos personalizar as mensagens como quisermos.


## Relacionamentos

- Ao adicionarmos mais modelos e tabelas na nossa aplicação precisamos pensar no relacionamento dos mesmos, neste exemplo de séries estaremos adicionando as classes e tabelas de Temporada e Episodios.

- Poderíamos criar o arquivo de modelo e a migration na mão mas também é possível criar isto pelo terminal de forma mais otimizada, basta darmos o comando ```php artisan make:model NomeModel -m```. O parâmetro -m serve para informarmos que desejamos criar o arquivo de migration junto da nossa modelo.

- Os relacionamentos no Laravel são feitos através de métodos, onde o nome do método será utilizado para obtermos os valores.


- Dentro do método informamos o relacionamento.

```
class Serie extends Model
{
    public function temporadas
    {
        return $this->hasMany(Temporada::class);
    }
}
```

- Com isso informamos que uma série possuí muitas temporadas.

- Informamos também que aquela classe pertence a outra.

```
class Temporada extends Model
{
    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }
}
```

- Com isso dizemos que uma temporada pertence a uma série.

- No arquivo de migrations precisamos colocar os relacionamentos também. Primeiro adicionamos um campo inteiro que será o Id de outra tabela, depois informamos que este campo é uma chave estrangeira.

```
public function up()
{
    Schema::create('temporadas', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('serie_id');
        $table->foreign('serie_id')->references('id')->on('series');
    });
}
```

## Exclusão com relacionamentos

- Na nossa aplicação criamos um relacionamento de temporadas e episódios com as séries e na hora de excluir uma série recebiamos um erro do banco de dados devido as chaves estrangeiras. Para solucionarmos este problema precisamos excluir os episódios da temporada, depois as temporadas da série e por último a série.

- Buscamos a série pelo ID do request e através dela chamamos os método each das temporadas, dentro dele chamamos o each para os episódios e deletamos os episódios. Após deletar os episódios deletamos as temporadas e por fim deletamos a série como visto abaixo:

```
public function destroy(Request $request)
{
    $serie = Serie::find($request->id);

    $serie->temporadas->each(function (Temporada $temporada){
        $temporada->episodios->each(function (Episodio $episodio){
            $episodio->delete();
        });
        $temporada->delete();
    });

    $serie->delete();
}
```

- No nosso caso é também interessante utilizar transações. Uma transação em banco dados significa que vamos executar uma sequência de querys e se alguma delas falhar nenhuma é executada. Isto garante que se algum erro aconteça durante o processo a execução não seja feita pela metade.

- Para informamos que o código a ser executado é uma transação utilizaremos ```DB::transaction``` passando uma função com os códigos a serem executados como parâmetro.

```
$nomeSerie = '';

DB::transaction(function () use ($serieId, &$nomeSerie){
    $serie = Serie::find($serieId);
    $nomeSerie = $serie->nome;

    $serie->temporadas->each(function (Temporada $temporada){
        $temporada->episodios->each(function (Episodio $episodio){
            $episodio->delete();
        });
        $temporada->delete();
    });

    $serie->delete();
});
```

- Note que colocamos um use junto da função, isto se deve ao fato de que estamos usando dados que estão fora da função e estamos alterando dados que também estão fora da função, então passamos o ID que recebemos do request e passamos como referência o nome da série.


## Alterando Dados

- Primeiro precisamos adicionar o botão de alterar o dado. Este botão chama uma função JavaScript para realizar uam troca de elemento fazendo com que o nome da série seja escondido e apareça uma caixa de texto para que o novo nome seja digitado ou o contrário.

- Ao digitar o nome e clicar no botão para salvar será executada outra função JavaScript, esta função cria um formulário com os dados que temos no input e faz uma requisição com esse form criado por nós para nosso arquivo de rotas. Nosso arquivo de rotas redireciona para a controller e lá fazemos a alteração do dado.


- Botões e Inputs:
```
<span id="nome-serie-{{ $serie->id }}">{{ $serie->nome }}</span>

<div class="input-group w-50" hidden id="input-nome-serie-{{ $serie->id }}">
    <input type="text" class="form-control" value="{{ $serie->nome }}">
    <div class="input-group-append">
        <button class="btn btn-primary" onclick="editarSerie({{ $serie->id }})">
            <i class="fas fa-check"></i>
        </button>
        @csrf
    </div>
</div>
```

- Funções JavaScript:

```
<script>
function toggleInput(serieId) {
    const nomeSerieEl = document.getElementById(`nome-serie-${serieId}`);
    const inputSerieEl = document.getElementById(`input-nome-serie-${serieId}`);
    if (nomeSerieEl.hasAttribute('hidden')) {
        nomeSerieEl.removeAttribute('hidden');
        inputSerieEl.hidden = true;
    } else {
        inputSerieEl.removeAttribute('hidden');
        nomeSerieEl.hidden = true;
    }
}

function editarSerie(serieId) {
    
    let formData = new FormData();
    const nome = document
        .querySelector(`#input-nome-serie-${serieId} > input`)
        .value;
    const token = document
        .querySelector(`input[name="_token"]`)
        .value;

    formData.append('nome', nome);
    formData.append('_token', token);

    const url = `/series/${serieId}/editaNome`;

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(() => {
        toggleInput(serieId);
        document.getElementById(`nome-serie-${serieId}`).textContent = nome;
    });
}
</script> 
```

- Contoller:

```
public function editaNome(int $id, Request $request)
{
    $novoNome = $request->nome;
    $serie = Serie::find($id);
    $serie->nome = $novoNome;
    $serie->save();
}
```

## Autenticação utilizando Laravel

- Com o comando ```php artisan make:auth``` o Laravel cria toda a estrutura necessária para criarmos uma autenticação no nosso sistema.

- Podemos utilizar o Middleware no construtor para verificar se o usuário está autenticado no sistema. Middleware é um mecânismo para filtrar requisições HTTP que vêm para nossa aplicação. Para verificarmos se um usuário está logado na aplicação basta adicionarmos o middleware com o auth no nosso método construtor.

- Podemos adicionar a autenticação nos métodos da controller com o ```Auth::check()```.

- Podemos adicionar a autenticação diretamente nas rotas com o ```->middleware('auth);```.

- Existem várias formas de se realizar esta verificação e todas são válidas de acordo com a aplicação, é necessário analisar e ver qual melhor se encaixa com sua aplicação.


## Autenticação própria

- Primeiro precisamos pegar os dados do nosso request na controller. Usaremos o ```$request->only(['dado1', 'dado2']);```.

- Com o método Auth::attempt nós faremos a verificação do login. Este método pega os dados do request e verifica se estes dados estão no banco, caso estejam o login será realizado e o usuário será salvo na sessão. Caso não esteja logado nós redirecionamos o usuário de volta para o form de login e exibimos um erro. Caso o usuário consiga realizar o login redirecionamos ele para a página inicial da aplicação.

```
public function entrar(Request $request)
{
    if(!Auth::attempt($request->only(['email', 'password']))){
        return redirect()->back()->withErrors('E-mail e/ou senha inválidos');
    }

    return redirect()->route('listar-series');
}
```

- Criamos a controle responsável por realizar o registro do usuário. Nela temos o método create que exibe a view de registro e o método store que realiza a inserção do usuário no sistema. 

- Pegaremos todos os dados da sessão exceto o token. ```$data = $request->except('_token');```.

- Precisamos criptografar esta senha para que a aplicação seja segura, faremos isso com o método make da classe Hash. Isso faz com que o campo password da nossa view seja transformado em hash e na hora de comparar a senha digitada no login com a senha cadastrada o Laravel faz de forma automática por termos usado o método make.

- Depois de criptografar a senha criamos o usuário.

- Podemos depois redirecionar ele para a view de login ou então realizar o login automáticamente.

```
public function store(Request $request)
{
    $data = $request->except('_token');

    $data['password'] = Hash::make($data['password']);

    $user = User::create($data);
    Auth::login($user);
}
```

- Para realizar o logout faremos na própria rota com o método logout do Auth.

```
Route::get('/sair', function(){
    Auth::logout();
    return redirect('/entrar');
});
```

- Note que ao tentar acessar uma página que não temos acesso somos redirecionados para a página de login do Laravel. Para mudarmos isso iremos em ```app/middleware/Authenticate.php``` e trocaremos a linha ```return route('login');``` para ```return '/entrar';``` que é a rota do nosso formulário de login.

- Podemos também criar o nosso próprio middleware. No terminal usaremos o comando ```php artisan make:middleware Nome```. No método handle deste arquivo criado adicionamos a verificação de autenticação com o ```!Auth::check()``` e retornamos o redirecionamento para a página de login caso a condição seja verdadeira. Onde usamos ```->middleware('auth');``` no nosso código podemos substituir por ```NomeCompletoClasse::class``` ou então no arquivo kernel podemos dar um nome a esta classe e utilizar este nome na chamada do middleware.
