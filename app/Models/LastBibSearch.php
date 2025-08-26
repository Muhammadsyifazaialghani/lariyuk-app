<?php
// app/Models/LastBibSearch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastBibSearch extends Model
{
    protected $fillable = [
        'bib_number',
        'name',
        'status',
        'checked_in_at',
    ];
}
