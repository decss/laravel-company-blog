<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['title', 'path', 'parent_id'];

    public function delete(array $options = [])
    {
        self::where('parent', $this->id)->delete();

        return parent::delete($options);
    }
}
