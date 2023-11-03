<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartmetricArtistFanMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chartmetric_artist_fan_metrics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->bigInteger('chartmetric_id')->nullable();
            $table->string('spotify_artist_id')->nullable();
            $table->string('artist_name')->nullable();
            $table->integer('spotify_followers')->nullable();
            $table->integer('spotify_followers_diff')->nullable();
            $table->float('spotify_followers_weekly_diff_percent')->nullable();
            $table->float('spotify_followers_monthly_diff_percent')->nullable();
            $table->integer('spotify_listners')->nullable();
            $table->integer('spotify_listners_diff')->nullable();
            $table->float('spotify_listners_weekly_diff_percent')->nullable();
            $table->float('spotify_listners_monthly_diff_percent')->nullable();
            $table->integer('instagram_followers')->nullable();
            $table->integer('instagram_followers_diff')->nullable();
            $table->float('instagram_followers_weekly_diff_percent')->nullable();
            $table->float('instagram_followers_monthly_diff_percent')->nullable();
            $table->integer('soundcloud_followers')->nullable();
            $table->integer('soundcloud_followers_diff')->nullable();
            $table->float('soundcloud_followers_weekly_diff_percent')->nullable();
            $table->float('soundcloud_followers_monthly_diff_percent')->nullable();
            $table->integer('tiktok_followers')->nullable();
            $table->integer('tiktok_followers_diff')->nullable();
            $table->float('tiktok_followers_weekly_diff_percent')->nullable();
            $table->float('tiktok_followers_monthly_diff_percent')->nullable();
            $table->integer('twitter_followers')->nullable();
            $table->integer('twitter_followers_diff')->nullable();
            $table->float('twitter_followers_weekly_diff_percent')->nullable();
            $table->float('twitter_followers_monthly_diff_percent')->nullable();
            $table->integer('youtube_subs')->nullable();
            $table->integer('youtube_subs_diff')->nullable();
            $table->float('youtube_subs_weekly_diff_percent')->nullable();
            $table->float('youtube_subs_monthly_diff_percent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chartmetric_artist_fan_metrics');
    }
}
