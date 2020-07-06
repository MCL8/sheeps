<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Report;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Report::all();

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
        $day = $request->input('day');

        $sheeps_alive = DB::table('sheep')->count();
        $sheeps_dead =  DB::table('events')->where('event_name', 'deleted')->count();
        $sheeps_total = (int)$sheeps_alive + (int)$sheeps_dead;

        $corrals = DB::table('sheep')->select(DB::raw('corral, count(*) as sheeps'))->groupBy('corral')->get();

        $max = $min = $corrals[0]->sheeps;
        $corralMax = $corralMin = $corrals[0]->corral;

        foreach ($corrals as $corral) {
            if ($max < $corral->sheeps) {
                $max = $corral->sheeps;
                $corralMax = $corral->corral;
            }
            if ($min > $corral->sheeps) {
                $min = $corral->sheeps;
                $corralMin = $corral->corral;
            }
        }

        $report = new Report();

        $report->day = $day;
        $report->sheeps_total = $sheeps_total;
        $report->sheeps_alive = $sheeps_alive;
        $report->sheeps_dead = $sheeps_dead;
        $report->corral_max = $corralMax;
        $report->corral_min = $corralMin;
        $report->created_at = now();
        $report->updated_at = now();

        $report->save();

        return response('the report is created', 201);
    }
}
