<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','activity_id','gender','sub_id'
       ];
    //faltan las relaciones con los otros modelos   
    //solo es con sub porque es tabla intermedia
    #entre event y activity y gender es un enum
    /* public function sub()
    {
        return $this->belongsTo(Sub::class);
    } */

    // Relación con la tabla 'activities'
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    // Relación con la tabla 'subs'
    public function sub()
    {
        return $this->belongsTo(Sub::class, 'sub_id');
    }



}
