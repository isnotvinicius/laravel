<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Serie;
use App\Services\{RemovedorSeries, CriadorSeries};
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware;


class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Serie::query()->orderBy('nome')->get();

        $mensagem = $request->session()->get('mensagem');


        return view('series.index', compact('series', 'mensagem'));
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request, CriadorSeries $criadorSerie)
    {

        $serie = $criadorSerie->criarSerie(
            $request->nome, 
            $request->qtd_temporadas, 
            $request->ep_por_temporada
        );

        $request->session()->flash('mensagem', "Série {$serie->nome} e suas temporadas com episódios criada com sucesso! Id = {$serie->id}");

        return redirect()->route('listar-series');
    }

    public function destroy(Request $request, RemovedorSeries $removedorSeries)
    {
        $nomeSerie = $removedorSeries->removerSerie($request->id);
        $request->session()->flash('mensagem', "Série $nomeSerie removida com sucesso");
        return redirect()->route('listar-series');
    }

    public function editaNome(int $id, Request $request)
    {
        $novoNome = $request->nome;
        $serie = Serie::find($id);
        $serie->nome = $novoNome;
        $serie->save();
    }
}
