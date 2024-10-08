<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagList extends Model
{
    use HasFactory;
    
    public $table = "tagslist";
    public $fillable = ['certificate', 'date', 'score'];

}
