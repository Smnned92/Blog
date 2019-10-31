<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    const IS_BANNED = 1;
    const IS_ACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        $this->hasMany(Post::class);
    }

    public function comments()
    {
        $this->hasMany(Comment::class);
    }

    // Add User
    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;
    }
    // Edit User
    public  function edit($fields)
    {
     $this->fill($fields);
     $this->password = bcrypt($fields['password']);
     $this->save();
    }
    //Delete User
    public function remove()
    {
        $this->delete();
    }

    // Avatar Download
    public function uploadAvatar($image)
    {
        if ($image == null) {
            return;
        }

        Storage::delete('uploads/' . $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAS('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }
    // Get Avatar
    public function getImage()
    {
        if ($this->image == null)
        {
            return '/img/no-user-image.png';
        }
        return '/uploads/' . $this->image;
    }
    // Check box for admin
    public  function makeAdmin()
    {
        $this->is_admin = 1;
        $this->save();
    }
    // Check box for user
    public  function makeNormal()
    {
        $this->is_admin = 0;
        $this->save();
    }
    // User Status Admin or User
    public function toggleAdmin()
    {
        if ($value =null)
        {
           return $this->makeNormal();
        }
        return $this->makeAdmin();
    }
    //User Ban Method
    public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }
    //User Active Method
    public function unban()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }
    // User Status Baned R Active
    public function toggleBan($value)
    {
        if ($value == null)
        {
            return $this->unban();
        }
        return $this->ban();
    }
}
