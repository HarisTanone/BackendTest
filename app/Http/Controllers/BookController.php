<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    private function validationRules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publish_date' => 'nullable|date',
            'author_id' => 'required|uuid|exists:authors,id',
        ];
    }
    // GET /Book - Retrieve a list of all Book
    public function index()
    {
        $Book = Book::with('author')->get();
        return response()->json($Book, 200);
    }

    // GET /Book/{id} - Retrieve details of a specific book
    public function show($id)
    {
        $book = Cache::remember("book_{$id}", 60, function () use ($id) {
            return Book::with('author')->findOrFail($id);
        });

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book, 200);
    }

    // POST /Book - Create a new book
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    // PUT /Book/{id} - Update an existing book
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book->update($request->all());
        Cache::forget("book_{$id}");

        return response()->json($book, 200);
    }

    // DELETE /Book/{id} - Delete a book
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();
        Cache::forget("book_{$id}");

        return response()->json(['message' => 'Book deleted'], 200);
    }
}
