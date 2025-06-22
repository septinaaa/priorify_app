<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function events()
    {
        $events = Todo::whereNotNull('deadline')->get()->map(function ($todo) {
            return [
                'title' => $todo->name,
                'start' => $todo->deadline,
                'color' => match ($todo->status) {
                    'Done' => 'green',
                    'In Progress' => 'orange',
                    default => 'blue',
                },
            ];
        });

        return response()->json($events);
    }
}