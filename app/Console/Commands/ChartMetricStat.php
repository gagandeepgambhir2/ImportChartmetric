<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SpotifyChartmetric;
use App\Jobs\ImportChartReport;

class ChartMetricStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:chartmetricStat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Chartmetric Stat';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $spotifyArray = SpotifyChartmetric::get();
        foreach ($spotifyArray as $artist) {
            ImportChartReport::dispatch($artist->spotify_artist_id)->onQueue('long-jobs');
        }
    }


}