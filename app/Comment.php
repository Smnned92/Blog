<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    // Comment allow
    public function allow()
    {
        $this->status = 1;
        $this->save();
    }

    // Comment disallow
    public  function  disallow()
    {
        $this->status = 0;
        $this->save();
    }

    // Comment allow or disallow
    public  function toggleStatus()
    {
        if ($this->status == 0)
        {
           return $this->allow();
        }
        return $this->disallow();
    }

    // Comment Delete
    public function remove()
    {
        $this->delete();
    }
}
