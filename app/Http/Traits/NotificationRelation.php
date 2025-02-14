<?php

namespace App\Http\Traits;

use App\XGNotification;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait NotificationRelation
{
    public function notifications(): MorphMany
    {
        return $this->morphMany(XGNotification::class,'model','model','model_id');
    }
}