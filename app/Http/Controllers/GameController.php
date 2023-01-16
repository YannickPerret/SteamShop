<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('games/index', ['games' => Game::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     **/
    public function create()
    {
        return view('games/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $game = Game::create($request->all());

        $name = $request->imagePath->getClientOriginalName();
        $destination = 'images/games/'.$game->id;
        $request->imagePath->move(public_path($destination), $name);

        $game->imagePath = $destination.'/'.$name;
        $game->save();

        return redirect()->route('games.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function promotion()
    {
        $games = Game::all()->where('releaseDate', '<', now())->sortByDesc('releaseDate')->take(5);
        return view('games/index', compact('games'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Game  $game
     *
     * @return View
     */
    public function show(Game $game): View
    {
        return view('games/show', compact('game'));
    }

    /**
     * @param  Game  $game
     *
     * @return void
     */
    public function purchase(Game $game)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Game  $game
     *
     * @return Response
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Models\Game  $game
     *
     * @return Response
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     *
     * @return Response
     */
    public function destroy(Game $game)
    {
        //
    }
}
