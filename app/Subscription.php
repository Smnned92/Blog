<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //Email add
    public static  function add($email)
    {
        $sub = new static();
        $sub->email = $email;
        $sub->token = str_random(100);
        $sub->save();

        return $sub;
    }

    //Email Delete
    public function remove()
    {
        $this->delete();
    }
}
