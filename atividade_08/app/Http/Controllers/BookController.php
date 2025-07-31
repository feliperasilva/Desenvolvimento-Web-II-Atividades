<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author')->paginate(20);
        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        $book->load(['author', 'publisher', 'category']);
        $users = User::all();
        return view('books.show', compact('book', 'users'));
    }

    public function createWithId()
    {
        $this->authorize('create', Book::class);
        return view('books.create-id');
    }

    public function storeWithId(Request $request)
    {
        $this->authorize('create', Book::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,svg'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('livros', 'public');
            $data['imagem'] = 'storage/' . $path;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function createWithSelect()
    {
        $this->authorize('create', Book::class);

        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    public function storeWithSelect(Request $request)
    {
        $this->authorize('create', Book::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);

        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,svg'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagem')) {
            if ($book->imagem && file_exists(public_path($book->imagem))) {
                unlink(public_path($book->imagem));
            }
            $path = $request->file('imagem')->store('livros', 'public');
            $data['imagem'] = 'storage/' . $path;
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        if ($book->imagem && file_exists(public_path($book->imagem))) {
            unlink(public_path($book->imagem));
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro deletado com sucesso.');
    }
}
