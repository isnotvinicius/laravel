<?php

namespace App\Services;

use App\{Serie, Temporada, Episodio};
use Illuminate\Support\Facades\DB;

class CriadorSeries
{
    public function criarSerie(string $nomeSerie, int $qtd_temporadas, int $ep_por_temporada): Serie
    {
        DB::beginTransaction();
        $serie = Serie::create(['nome' => $nomeSerie]);
        $this->criaTemporadas($serie, $qtd_temporadas, $ep_por_temporada);
        DB::commit();

        return $serie;
    }

    public function criaTemporadas(Serie $serie, int $qtd_temporadas, int $ep_por_temporada)
    {
        for($i = 1; $i <= $qtd_temporadas; $i++){
            $temporada = $serie->temporadas()->create(['numero' => $i]);

            $this->criaEpisodios($temporada, $ep_por_temporada);
        }
    }

    public function criaEpisodios(Temporada $temporada, int $ep_por_temporada)
    {
        for($j = 1; $j <= $ep_por_temporada; $j++){
            $temporada->episodios()->create(['numero' => $j]);
        }
    }
}
