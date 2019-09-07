<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Game;
use App\User;
use App\RuleSet;
use App\Participate;

class GameController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Games Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the Games.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Este controlador só é acessível a utilizadores registados e verificados
        $this->middleware('auth');
        $this->middleware('verified');

        // O método 'manage' só é acessível ao master
        $this->middleware('master')->only('manage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $user = Auth::user();
        
        $games = Game::all()->filter(function ($value, $key) use($user){
            
            return ($value->master() == $user || $value->status=='accepting') || $value->players->contains($user);
        });
        
        foreach ($games as $game)
        {
            $game->playersCount = $game->players->count();
            $game->gestor = $game->master->username;
            
            if($game->master->id  == Auth::id())
            {
                $game->manageable=1;
            }
                        
            if($game->status=='accepting')
            {
                if($game->players->contains(Auth::user())) $game->leaveable=1;
                else $game->joinable=1;
            }
            if($game->status=='running' && $game->players->contains(Auth::user())) $game->playable=1;
        }

        return view('layouts.games.index')->with([
            'games' => $games,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $masters = User::all()->pluck('username', 'id');
        $masters->prepend('Selecione um Master', 0);

        $rules = RuleSet::all()->pluck('name', 'id');
        $rules->prepend('Selecione as regras', 0);
        
        return view('games.create')->with([
            'masters' => $masters,
            'rules' => $rules,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar os dados de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'password' => 'string|min:6|confirmed',
            'master' => 'required|exists:users,id',
            'rules' => 'required|exists:rule_sets,id',
        ]);

        // Persistir novo game na BD
        $game = new Game;
        $game->name = $request->name;
        $game->password = Hash::make($request->password);
        $game->status = 'accepting';
        $game->master_id = $request->master;
        $game->rules_id = $request->rules;
        $game->save();

        //Redirecionar para a lista de jogos
        return redirect()->route('games.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        //
    }

    /**
     * Join a specified game.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function join($id)
    {
        // Definir o jogo
        $game = Game::find($id);

        // Voltar à página de jogos se o o jogo não estiver em "accepting". ACRESCENTAR MENSAGEM ERRO
        if($game->status != 'accepting') return redirect()->route('games.index');

        // Registar o user (Auth::id()) no game
        $game->players()->syncWithoutDetaching([Auth::id()]);

        // Se o número de players no jogo chegar ao número previsto, o jogo deve arrancar
        $maxPlayers = $game->maxPlayers();
        if ($game->players->count() == $maxPlayers)
        {
            $game->update(['status' => 'running', 'period' => 1, 'phase' => 'pre']);


            // atribuir roles (e inventários)
            $roleList = collect();

            for ($i = 1; $i <= $maxPlayers; $i++)
            {
                $roleList = $roleList->concat([$i]);
            }
            $shuffledList = $roleList->shuffle();
            foreach ($game->players as $player)
            {
                if(!isset($player->participate->role_id))
                {
                    $new_role = $shuffledList->pop();
                    
                    $game->players()->detach($player->id);
                    $game->players()->attach($player->id, ['role_id' => $new_role, 'inventory' => Participate::new_inventory($game->numberOfTanks())]);
                }
            }

        }

        //Redirecionar para a lista de jogos
        return redirect()->route('games.index');
    }


    /**
     * Leave a specified game.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leave($id)
    {
        // Definir o jogo
        $game = Game::find($id);

        // Eliminar o registo do user no game
        if ($game->status == 'accepting')
        $game->players()->detach(Auth::id());
        //Redirecionar para a lista de jogos
        return redirect()->route('games.index');
    }

}
