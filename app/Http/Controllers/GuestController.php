<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class GuestController extends Controller
{
    public function events() {
        $events = Event::latest('datetime')->get();
        return view('welcome', [
            'events' => $events
        ]);
    }
}
