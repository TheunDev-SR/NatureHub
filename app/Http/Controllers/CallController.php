<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort_by = $request->query('sort_by', 'id');
        $sort_dir = $request->query('sort_dir', 'desc');

        $messages = Call::orderBy('is_read', 'asc')
            ->orderBy($sort_by, $sort_dir)
            ->paginate(10);

        return view('admin.call', compact('messages'));
    }

    public function markAsRead($id)
    {
        $message = Call::findOrFail($id);
        $message->is_read = true;
        $message->save();

        return redirect()->route('admin.call')->with('success', 'Message marked as read.');
    }

    public function markAsUnread($id)
    {
        $message = Call::findOrFail($id);
        $message->is_read = false;
        $message->save();

        return redirect()->route('admin.call')->with('success', 'Message marked as unread.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'message' => 'required|string',
        ]);

        Call::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Pesan Anda telah dikirim!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Call $call)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Call $call)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Call $call)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Call $call)
    {
        $call->delete();
        return redirect()->route('admin.call')->with('success', 'Message deleted successfully');
    }
}
