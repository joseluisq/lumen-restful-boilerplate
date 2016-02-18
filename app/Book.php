<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model {

  protected $fillable = ["title", "author", "isbn"];
  protected $dates = ["started_at", "published_at"];
  public static $rules = [
      // Validation rules
  ];

  // Relationships
}
