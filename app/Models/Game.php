<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Game extends Model
{
    protected $fillable = ['name', 'price', 'description', 'image_path', 'release_date'];

    function buyByUser(User $user)
    {
        if ($this->release_date < now() && !$user->hasGame($this) && $this->price <= $user->credits) {
            if ($this->hasPromotion)
                $user->credits -= $this->price - $this->hasPromotion->new_price;
            else
                $user->credits -= $this->price;
            $user->save();

            Library::create(['user_id' => $user->id, 'game_id' => $this->id]);

            return true;
        }
        return false;
    }

    function hasPromotion()
    {
        return $this->hasOne(Promotion::class);
    }

    function saveImage ($request){

        //$name = $request->image_path->getClientOriginalName();
        //$destination = 'images/games/'.$name;

        $this->image_path = $request->image_path->store('games', 's3');

        # If you do not have S3 as your default:
        //Storage::disk('s3')->put($destination, file_get_contents($request->image_path));
        
        //$this->image_path = $destination.'/'.$name;
    }
}
