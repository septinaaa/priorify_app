<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function main()
    {
        $todos = Todo::all();
        return view('main', compact('todos'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        Todo::create($request->only('name', 'description', 'status', 'deadline'));

        return redirect('/main');
    }

    public function edit(Todo $todo)
    {
        return view('edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        $todo->update($request->only('name', 'description', 'status', 'deadline'));

        return redirect('/main');
    }

    public function delete(Todo $todo)
    {
        $todo->delete();
        return redirect('/main');
    }

    public function updateOrder(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        foreach ($ids as $index => $id) {
            Todo::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:todos,id',
            'status' => 'required|string',
        ]);

        Todo::where('id', $request->id)->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    // ✅ Fungsi tambahan untuk menambahkan task dari kalender
    public function apiStore(Request $request)
{
    $todo = new Todo();
    $todo->name = $request->name; // sesuai input dari JavaScript
    $todo->deadline = $request->deadline; // sesuai input dari JavaScript
    $todo->user_id = auth()->id(); // jika login
    $todo->status = 'pending'; // default status
    $todo->description = '-'; // default desc (optional)
    $todo->save();

    return response()->json(['success' => true]);
}
}