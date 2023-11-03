<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotifyChartmetric extends Model
{
    protected $table = 'spotify_chartmetric';

    protected $fillable = [
        'spotify_artist_id',
    ];
}