<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Sheep;
use App\Event;

class SheepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sheeps = Sheep::all();
        $result = [];

        foreach ($sheeps as $sheep) {
            $result['corral' . $sheep['corral']][] = 'Овечка ' . $sheep['id'];
        }

        return response($result, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('corral') && $request->has('day')) {
            $corral = $request->input('corral');
            $day = $request->input('day');

            $sheep = new Sheep();
            $sheep->corral = $corral;
            $sheep->created_at = now();
            $sheep->updated_at = now();

            $sheep->save();
            $sheep_id = $sheep->id;

            $event_name = 'created';
            $event = new Event();
            $event->log(compact('day', 'sheep_id', 'corral', 'event_name'));

            return response('the sheep is created', 201);
        }

        return response('corral or day is not defined', 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->has('corral') && $request->has('day')) {
            $corral = $request->input('corral');
            $day = $request->input('day');

            $sheep = Sheep::find($id);
            if (is_null($sheep)) {
                return response('the sheep not found', 404);
            }
            $corralOld = $sheep->corral;
            $sheep->corral = $corral;
            $sheep->save();

            if ($corralOld != $corral) {
                $sheep_id = $sheep->id;
                $event_name = "moved from {$corralOld} to {$corral}";
                $event = new Event();
                $event->log(compact('day', 'sheep_id', 'corral', 'event_name'));
            }

            return response('the sheep is updated', 204);
        }

        return response('corral or day is not defined', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sheep = Sheep::findOrFail($id);
        $sheep->delete();

        return response('the sheep is deleted', 204);
    }
}
