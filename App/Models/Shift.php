<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_FINISHED = 2;
}
