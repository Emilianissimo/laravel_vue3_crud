<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body'];

    protected $appends = ['real_image'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('F d, Y');
    }

    public function setCategory(?int $id): void
    {
        if (is_null($id)){
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    public static function add(array $fields): self
    {
        $element = new self;
        $element->fill($fields);
        $element->save();

        return $element;
    }

    public function edit(array $fields): void
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove(): void
    {
        $this->removeImage();
        $this->delete();
    }

    public function removeImage()
    {
        if ($this->image !=null) {
            Storage::delete('uploads/posts/'. $this->image);
        }
    }

    public function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $this->removeImage();
        $filename = Str::random(10) . '.' . $image->extension();
        $image->storeAs('uploads/posts', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        if ($this->image == null) {
            return '/img/no-image.webp';
        }
        return '/uploads/posts/' . $this->image;
    }

    /**
     * Get the user's first name.
     *
     * @return string
     */
    protected function getRealImageAttribute(): string
    {
        return $this->getImage($this->image);
    }

}
