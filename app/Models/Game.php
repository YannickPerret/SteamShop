<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

        $name = $request->image_path->getClientOriginalName();
        $destination = 'images/games/'.$this->id;
        $request->image_path->move(public_path($destination), $name);

        $this->image_path = $destination.'/'.$name;
    }
}
