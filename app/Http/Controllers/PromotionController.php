<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Game $game)
    {
        return view('promotion.create', compact('game'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Game $game)
    {
        //faire un test si une promotion exsite déjà pour le jeu
        $promotion = $request->validate([
            'new_price'=> 'required|min:0',
            'start_promo' => 'required',
            'end_promo' => 'required',
       ]);

       if($promotion['start_promo'] >= now() && $promotion['end_promo'] >= now() && $promotion['end_promo'] >= $promotion['start_promo']){
        if($promotion['new_price'] <= $game->price){
            $newPromo = new Promotion($promotion);
            $newPromo->game_id = $game->id;

            $newPromo->save();
        }
        else
            return back()->with('message', 'Le prix en promotion est plus grand que le prix du jeu');
       }
       return back()->with('message', 'Merci de vérifier les dates de promotions');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
