<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartmetricArtistFan extends Model
{

    protected $table = 'chartmetric_artist_fan_metrics';

    protected $fillable = [
        'chartmetric_id',
        'spotify_artist_id', 
        'artist_name',
        'spotify_followers',
        'spotify_followers_diff',
        'spotify_followers_weekly_diff_percent',
        'spotify_followers_monthly_diff_percent',
        'spotify_listners',
        'spotify_listners_diff',
        'spotify_listners_weekly_diff_percent',
        'spotify_listners_monthly_diff_percent',
        'instagram_followers',
        'instagram_followers_diff',
        'instagram_followers_weekly_diff_percent',
        'instagram_followers_monthly_diff_percent',
        'soundcloud_followers',
        'soundcloud_followers_diff',
        'soundcloud_followers_weekly_diff_percent',
        'soundcloud_followers_monthly_diff_percent',
        'tiktok_followers',
        'tiktok_followers_diff',
        'tiktok_followers_weekly_diff_percent',
        'tiktok_followers_monthly_diff_percent',
        'twitter_followers',
        'twitter_followers_diff',
        'twitter_followers_weekly_diff_percent',
        'twitter_followers_monthly_diff_percent',
        'youtube_subs',
        'youtube_subs_diff',
        'youtube_subs_weekly_diff_percent',
        'youtube_subs_monthly_diff_percent',
        'date'
    ];
}