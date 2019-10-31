<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use Sluggable;

    const  IS_DRAFT = 0;
    const  IS_PUBLIC = 1;

    protected $fillable = ['title', 'content'];

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    // Pos add
    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    //Post edit
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    //Post Delete
    public function remove()
    {
        // Delete post image
        $this->delete();
    }

    // Image Download
    public function uploadImage($image)
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

    // Get Image Method
    public function getImage()
    {
        if ($this->image == null)
        {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->image;
    }

    // Category Install
    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }

        $this->category_id()->$id;
        $this->save();
    }

    // Tags Install
    public function setTags($ids)
    {
        if ($ids == null) {
            return;
        }

        $this->tags()->sync($ids);

    }

    // Status Method for toggleStatus
    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    // Status Method for toggleStatus
    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    // Status Toggle
    public function toggleStatus($value)
    {
        if ($value == null) {
            return $this->setDraft();
        }
        return $this->setPublic();

    }

    // Status Method for Featured
    public function setFeatured()
    {
        $this->is_featured = 1;
        $this->save();
    }

    // Status Method for Featured
    public function setStandart()
    {
        $this->is_featured = 0;
        $this->save();
    }

    // Status Featured
    public function toggleFeatured($value)
    {
        if ($value == null) {
            return $this->setStandart();
        }
        return $this->setFeatured();

    }

}
