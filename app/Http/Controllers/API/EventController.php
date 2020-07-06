<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $day = $request->input('day');
        $sheep_id = $request->input('sheep_id');
        $corral = $request->input('corral');
        $event_name = $request->input('event_name');

        $event = new Event();
        $event->log(compact('day', 'sheep_id', 'corral', 'event_name'));
    }
}
