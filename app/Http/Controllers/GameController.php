<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Aws\AwsClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\PostObjectV4;
use Exception;

class GameController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
    }
    
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
        $bucket = env('AWS_BUCKET');
        $key = 'games/images/' . \Str::random(40);
        // Set some defaults for form input fields
        $formInputs = ['acl' => 'private', 'key' => $key];

        // Construct an array of conditions for policy
        $options = [
            ['acl' => 'private'],
            ['bucket' => env('AWS_BUCKET')],
            ['starts-with', '$key', $key],
            ["starts-with", "Content-Type", ""],
            //['eq', '$key', $key],
        ];
        // Optional: configure expiration time string
        $expires = '+8 hours';

        $postObject = new \Aws\S3\PostObjectV4($this->client,$bucket,$formInputs,$options,$expires);        

        $formAttributes = $postObject->getFormAttributes();
        $formInputs = $postObject->getFormInputs();

        return view('games/create')->with(compact('formInputs', 'formAttributes'))->with("imageURL", $key);
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
        $games = Game::all()->where('release_date', '<', now())->sortByDesc('release_date')->take(5);
        return view('games/index', compact('games'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Game  $game
     *
     * @return View
     */
    public function show(Request $request, Game $game)
    {

        if($request->prefers(['text', 'image']) == 'image') {
            return redirect(Storage::disk('s3')->temporaryUrl($game->image_path, now()->addMinutes(2)));
        }
        return view('games/show', compact('game'));
    }

    /**
     * @param  Game  $game
     *
     * @return RedirectResponse
     */
    public function purchase(Game $game): RedirectResponse
    {
        if ($game->buyByUser(Auth::user())) {
            return redirect()->route('games.index');
        }
        return redirect()->back()->with(
            'error',
            'Achat impossible'
        );
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
