<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Post extends Model
{
  use Sluggable;

  const IS_DRAFT = 0;
  const IS_PUBLIC = 1;

  protected $fillable = ['title', 'content', 'date', 'description'];

  public function sluggable()
  {
    return [
      'slug' => [
        'source' => 'title'
      ]
    ];
  }

  //Связи
  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function author()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
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


  public static function add($fields)
  {
    $post = new static;
    $post->fill($fields);
    $post->save();

    return $post;
  }

  public function edit($fields)
  {
    $this->fill($fields);
    // Чтобы поменять slug раскомментировать след строку
    // $this->slug = null; 
    $this->save();
  }

  public function remove()
  {
    $this->removeImage();
    $this->delete();
  }

  public function removeImage()
  {
    if($this->image != null)
    {Storage::delete('uploads/' . $this->image);}
  }

  //Загрузка картинки
  public function uploadImage($image)
  {
    if($image == null) return;
    $this->removeImage();
    $filename = str_random(10) . '.' . $image->extension();
    //$filename = Str::random(10) . '.' . $image->extension();
    $image->storeAs('uploads', $filename);
    $this->image = $filename;
    $this->save();
  }

  public function getImage()
  {
    if($this->image == null) return '/img/no-image.png';

    return '/uploads/' . $this->image;
  }

  public function setCategory($id)
  {
    if($id == null) return;

    $this->category_id = $id;
    $this->save(); 
  }

  public function setTags($ids)
  {
    if($ids == null) return;

    $this->tags()->sync($ids);
  }

  //Для установки черновиков
  public function setDraft()
  {
    $this->status = Post::IS_DRAFT;
    $this->save();
  }

  public function setPublic()
  {
    $this->status = Post::IS_PUBLIC;
    $this->save();
  }

  public function toggleStatus($value)
  {
    if($value == null) return $this->setDraft();

    return $this->setPublic();
  }


  //Для установки Рекомендованных
  public function setFeatured()
  {
    $this->is_featured = 1;
    $this->save();
  }

  public function setStandart()
  {
    $this->is_featured = 0;
    $this->save();
  }

  public function toggleFeatured($value)
  {
    if($value == null) return $this->setStandart();

    return $this->setFeatured();
  }

  public function setDateAttribute($value)
  {
    $date = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    $this->attributes['date'] = $date;
  }

  public function getDateAttribute($value)
  {
    $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    return $date;
  }

  public function getCategoryTitle() 
  {
    if($this->category != null)
    {return $this->category->title;}

    return 'Без категории';
  }

  public function getTagsTitles()
  {
    if(!$this->tags->isEmpty())
    {return implode(', ', $this->tags->pluck('title')->all());}

    return 'Нет тегов';
  }

  public function getCategoryID()
  {
    return $this->category != null ? $this->category->id : null;
  }

  public function getDate()
  {
    return Carbon::createFromFormat('d/m/Y', $this->date)->format('F d, Y');
  }

  public function hasPrevious()
  {
    return self::where('id', '<', $this->id)->max('id');
  }

  public function hasNext()
  {
    return self::where('id', '>', $this->id)->min('id');
  }

  public function getPrevious()
  {
    $postID = $this->hasPrevious();
    
    return self::find($postID);
  }

  public function getNext()
  {
    $postID = $this->hasNext();
    
    return self::find($postID);
  }

  public function related()
  {
    return self::all()->except($this->id);
  }

  public function hasCategory()
  {
    return $this->category != null ? true : false;
  }

  public static function getPopularPosts()
  {
    return self::orderBy('views', 'desc')->take(3)->get();
  }

  public static function getFeaturedPosts()
  {
    return self::where('is_featured', 1)->take(3)->get();
  }

  public static function getRecentPosts()
  {
    return self::orderBy('date', 'desc')->take(4)->get();
  }

  public function getComments()
  {
    return $this->comments()->where('status', 1)->get();
  }
}
