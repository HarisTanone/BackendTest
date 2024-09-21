<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    // GET /Author - Retrieve a list of all Author
    public function index()
    {
        $Author = Author::select('id', 'name', 'bio', 'birth_date')->get();
        return response()->json($Author, 200);
    }

    // GET /Author/{id} - Retrieve details of a specific author
    public function show($id)
    {
        $author = Cache::remember("author_{$id}", 60, function () use ($id) {
            return Author::select('id', 'name', 'bio', 'birth_date')->findOrFail($id);
        });

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json($author, 200);
    }


    // POST /Author - Create a new author
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $author = Author::create($request->all());

        return response()->json($author, 201);
    }

    // PUT /Author/{id} - Update an existing author
    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $author->update($request->all());
        Cache::forget("author_{$id}");

        return response()->json([
            'message' => 'Author updated',
            'author' => $author
        ], 200);
    }

    // DELETE /Author/{id} - Delete an author
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();
        Cache::forget("author_{$id}");

        return response()->json(['message' => 'Author deleted'], 200);
    }

    // GET /Author/{id}/books - Retrieve all books by a specific author
    public function books($id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json($author->books, 200);
    }
}
