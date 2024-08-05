<?php

namespace App\Http\Controllers;

use Carbon;
use Parsedown;
use App\Models\Like;
use App\Models\User;
use App\Models\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query()->whereNotNull('approved_at');

        $sortField = $request->input('sort_by', 'updated_at');
        $sortDirection = $request->input('sort_dir', 'desc');

        try {
            $query->orderBy($sortField, $sortDirection);
        } catch (\Exception $e) {
            $query->orderBy('updated_at', 'desc');
        }

        $articles = $query->paginate(3);
        return view('articles.index', ['title' => 'All Article', 'articles' => $articles]);
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = [
                'title' => $request->title,
                'content' => (new Parsedown())->text($request->content),
                'user_id' => Auth::id(),
            ];

            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $imageName = time() . '.' . $request->image->extension();
                $request->image->storeAs('public/images', $imageName);
                $data['image'] = 'images/' . $imageName;
            }

            if ($request->has('approved')) {
                $data['approved_at'] = now();
            }

            Article::create($data);

            return redirect()->route('articles.index')->with('success', 'Article created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create article. Please try again.']);
        }
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return view('articles.show', compact('article'));
    }

    public function adminShow($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return view('admin.articles.show', compact('article'));
    }

    public function author(User $user)
    {
        $articles = $user->articles()->paginate(3);
        return view('articles.index', [
            'articles' => $articles,
            'title' => 'Article by ' . $user->name
        ]);
    }

    public function edit($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article = Article::where('slug', $slug)->firstOrFail();
        $data = [
            'title' => $request->title,
            'content' => $request->content,
        ];

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::delete('public/' . $article->image);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $article->update($data);

        return redirect()->route('articles.index')->with('success', 'Article updated successfully');
    }

    public function destroy($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        if ($article->image) {
            Storage::delete('public/' . $article->image);
        }

        $article->delete();
        return redirect()->route('admin.articles')->with('success', 'Article deleted successfully');
    }

    public function like(Article $article)
    {
        $article->likes()->create(['user_id' => auth()->id()]);

        return response()->json([
            'message' => 'Liked',
            'likes_count' => $article->likes()->count(),
            'is_liked' => true,
        ])->header('Cache-Control', 'no-store, must-revalidate');
    }

    public function unlike(Article $article)
    {
        $article->likes()->where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'Unliked',
            'likes_count' => $article->likes()->count(),
            'is_liked' => false,
        ])->header('Cache-Control', 'no-store, must-revalidate');
    }
}
