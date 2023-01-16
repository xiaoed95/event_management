<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class GuestController extends Controller
{
    public function events() {
        $events = Event::approved()->latest('datetime')->get(); //added approved function and scope from readibility
        return view('welcome', [
            'events' => $events
        ]);
    }
}
