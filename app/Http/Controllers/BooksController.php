<?php

namespace App\Http\Controllers;

class BooksController extends Controller {

  const MODEL = "App\Book";

  use RESTActions;

  public function index() {
    $Books = Book::all();
    return response()->json($Books);
  }

}
