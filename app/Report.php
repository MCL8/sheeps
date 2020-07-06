<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    /**
     * @param $day
     * @return bool
     */
    public function makeReport($day)
    {
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

        $this->day = $day;
        $this->sheeps_total = $sheeps_total;
        $this->sheeps_alive = $sheeps_alive;
        $this->sheeps_dead = $sheeps_dead;
        $this->corral_max = $corralMax;
        $this->corral_min = $corralMin;
        $this->created_at = now();
        $this->updated_at = now();

        return $this->save();
    }
}
