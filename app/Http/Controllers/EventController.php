<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        $sortField = $request->input('sort_by', 'start_time');
        $sortDirection = $request->input('sort_dir', 'asc');

        try {
            $query->orderBy($sortField, $sortDirection);
        } catch (\Exception $e) {
            $query->orderBy('start_time', 'asc');
        }

        $events = $query->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $provinces = Province::all();
        $regencies = Regency::all();
        $districts = District::all();
        $villages = Village::all();
        return view('admin.events.create', compact('provinces', 'regencies', 'districts', 'villages'));
    }

    public function getRegencies($provinceId)
    {
        $regencies = Regency::where('province_id', $provinceId)->pluck('name', 'id');
        return response()->json($regencies);
    }

    public function getDistricts($regencyId)
    {
        $districts = District::where('regency_id', $regencyId)->pluck('name', 'id');
        return response()->json($districts);
    }

    public function getVillages($districtId)
    {
        $villages = Village::where('district_id', $districtId)->pluck('name', 'id');
        return response()->json($villages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'required|string|max:255'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'url' => $request->url,
            'user_id' => Auth::id(),
            'slug' => Str::slug($request->name, '-') . '-' . time(),
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $event = Event::create($data);

        if ($event) {
            return redirect()->route('admin.events')->with('success', 'Event created successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to create event');
        }
    }

    public function edit($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $provinces = Province::all();
        $regencies = Regency::all();
        $districts = District::all();
        $villages = Village::all();
        return view('admin.events.edit', compact('event', 'provinces', 'regencies', 'districts', 'villages'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'url' => 'required|string|max:255',
            'end_time' => 'required|date|after_or_equal:start_time',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::where('slug', $slug)->firstOrFail();
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'url' => $request->url
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($event->image) {
                Storage::delete('public/' . $event->image);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $event->update($data);

        return redirect()->route('admin.events')->with('success', 'Event updated successfully');
    }

    public function destroy($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if ($event->image) {
            Storage::delete('public/' . $event->image);
        }

        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted successfully');
    }
}
