<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * @param array $fields
     * @return bool
     */
    public function log(array $fields)
    {
        $this->day = $fields['day'];
        $this->sheep_id = $fields['sheep_id'];
        $this->corral = $fields['corral'];
        $this->event_name = $fields['event_name'];
        $this->created_at = now();
        $this->updated_at = now();

        return $this->save();
    }
}
