<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::query();

        $sortField = $request->input('sort_by', 'start_time');
        $sortDirection = $request->input('sort_dir', 'asc');

        try {
            $query->orderBy($sortField, $sortDirection);
        } catch (\Exception $e) {
            $query->orderBy('start_time', 'asc');
        }

        $campaigns = $query->paginate(10);
        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            // 'slug' => Str::slug($request->name, '-') . '-' . time(),
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $campaign = Campaign::create($data);

        if ($campaign) {
            return redirect()->route('admin.campaigns')->with('success', 'Campaign created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create campaign');
        }
    }

    public function edit($slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        $data = [
            'title' => $request->title,
            'name' => $request->name,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'slug' => Str::slug($request->name, '-') . '-' . time(),
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($campaign->image) {
                Storage::delete('public/' . $campaign->image);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $campaign->update($data);

        return redirect()->route('admin.campaigns')->with('success', 'Campaign updated successfully');
    }

    public function destroy($slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        if ($campaign->image) {
            Storage::delete('public/' . $campaign->image);
        }

        $campaign->delete();
        return redirect()->route('admin.campaigns')->with('success', 'Campaign deleted successfully');
    }

    public function like(Campaign $campaign)
    {
        $campaign->likes()->create(['user_id' => auth()->id()]);

        return response()->json([
            'message' => 'Liked',
            'likes_count' => $campaign->likes()->count(),
            'is_liked' => true,
        ])->header('Cache-Control', 'no-store, must-revalidate');
    }

    public function unlike(Campaign $campaign)
    {
        $campaign->likes()->where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'Unliked',
            'likes_count' => $campaign->likes()->count(),
            'is_liked' => false,
        ])->header('Cache-Control', 'no-store, must-revalidate');
    }
}
