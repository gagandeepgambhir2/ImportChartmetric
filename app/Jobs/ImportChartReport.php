<?php

namespace App\Jobs;

use Carbon\Carbon;

use App\Services\ChartMetricService;
use App\Models\ChartmetricArtistFan;
use App\Models\SpotifyChartmetric;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportChartReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $artistId;
    
    public function __construct($artistId)
    {
        $this->artistId = $artistId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->saveArtist($this->artistId);
    }
    
    public function saveArtist($artistId)
    {
        $artist = explode(":",$artistId);
        $chartMetricService = new ChartMetricService;
        $sources = ['spotify','instagram','soundcloud','tiktok','twitter','youtube_channel'];
        $since = '2023-01-01';
        $until = '2023-11-01';
        $stat = [];
        if($artist[2] && !empty($artist[2]))
        {
            $spotifyArtistId = $artist[2];
            $serviceData = $chartMetricService->getIdsBySpotify($artist[2]);
            if(isset($serviceData['obj']) && isset($serviceData['obj'][0]->cm_artist))
            {
                $artistId = $serviceData['obj'][0]->cm_artist;
                $artistName = $serviceData['obj'][0]->artist_name;
                foreach($sources as $source) {
                    $response = $chartMetricService->getStat($serviceData['obj'][0]->cm_artist,$source,$since,$until);
                    if(isset($response['obj']->followers) && $response['obj']->followers)
                    {
                        $followers = $response['obj']->followers;
                        
                        if($source == 'spotify')
                        {
                            $spotifyListner = $response['obj']->listeners;
                            
                            foreach($spotifyListner as $listner)
                            {
                                $date = Carbon::parse($listner->timestp)->format('Y-m-d');
                                $stat['spotify']['listner'][$date][$artistId] = [
                                    'value'=> $listner
                                ];
                            }
                        }

                        foreach($followers as $follower)
                        {
                            $date = Carbon::parse($follower->timestp)->format('Y-m-d');
                            if($source == 'spotify')
                            {
                               $stat['spotify']['followers'][$date][$artistId] = [
                                    'value'=> $follower
                                ];
                            }
                            
                            if($source == 'instagram')
                            {
                                $stat['instagram']['followers'][$date][$artistId] = [
                                    'value'=> $follower
                                ];
                            }
                            
                            if($source == 'soundcloud')
                            {
                                $stat['soundcloud']['followers'][$date][$artistId] = [
                                    'value'=> $follower
                                ];
                            }
                            
                            if($source == 'tiktok')
                            {
                                $stat['tiktok']['followers'][$date][$artistId] = [
                                    'value'=> $follower
                                ];
                            }
                            
                            if($source == 'twitter')
                            {
                                $stat['twitter']['followers'][$date][$artistId] = [
                                    'value'=> $follower
                                ];
                            }
                        }
                        
                    }

                    if($source == 'youtube_channel' && isset($response['obj']->subscribers) && $response['obj']->subscribers)
                    {
                        $youTubeChannel = $response['obj']->subscribers;
                        foreach($youTubeChannel as $channel)
                        {
                            $date = Carbon::parse($channel->timestp)->format('Y-m-d');
                            $stat['youtube_channel']['subscribers'][$date][$artistId] = [
                                'value'=> $channel
                            ];
                        }
                    }
                }
                $this->putUser($artistId,$artistName,$stat,$spotifyArtistId);
            }
        }
    }

    public function putUser($artistId,$aritstName,$stats,$spotifyArtistId)
    {
        $since = Carbon::parse('2023-01-01');
        $until = '2023-11-01';
        $dates = [];
        // Loop through the dates and add them to the array
        while ($since <= $until) {
            $dates[] = $since->toDateString();
            $since->addDay();
        }
        
        $streamingArray = [];
        
        // Render the dates
        foreach ($dates as $date) {
            $entry = [
                'date' => $date,
                'chartmetric_id' => $artistId,
                'spotify_artist_id' => $spotifyArtistId,
                'artist_name' => $aritstName,
                'created_at' => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),
            ];
            if(isset($stats['spotify']['followers'][$date][$artistId]))
            {
                $spotifyFollower = $stats['spotify']['followers'][$date][$artistId];
                $entry['spotify_followers'] = $spotifyFollower['value']->value;
                $entry['spotify_followers_diff'] = $spotifyFollower['value']->diff;
                $entry['spotify_followers_weekly_diff_percent'] = $spotifyFollower['value']->weekly_diff_percent;
                $entry['spotify_followers_monthly_diff_percent'] = $spotifyFollower['value']->monthly_diff_percent;

            }
            else
            {
                $entry['spotify_followers'] = null;
                $entry['spotify_followers_diff'] = null;
                $entry['spotify_followers_weekly_diff_percent'] = null;
                $entry['spotify_followers_monthly_diff_percent'] = null;
            }

            if(isset($stats['spotify']['listner'][$date][$artistId]))
            {
                $spotifyListner = $stats['spotify']['listner'][$date][$artistId];
                $entry['spotify_listners'] = $spotifyListner['value']->value;
                $entry['spotify_listners_diff'] = $spotifyListner['value']->diff;
                $entry['spotify_listners_weekly_diff_percent'] = $spotifyListner['value']->weekly_diff_percent;
                $entry['spotify_listners_monthly_diff_percent'] = $spotifyListner['value']->monthly_diff_percent;

            }
            else
            {
                $entry['spotify_listners'] = null;
                $entry['spotify_listners_diff'] = null;
                $entry['spotify_listners_weekly_diff_percent'] = null;
                $entry['spotify_listners_monthly_diff_percent'] = null;
            }

            if(isset($stats['instagram']['followers'][$date][$artistId]))
            {
                $instagramFollower = $stats['instagram']['followers'][$date][$artistId];
                $entry['instagram_followers'] = $instagramFollower['value']->value;
                $entry['instagram_followers_diff'] = $instagramFollower['value']->diff;
                $entry['instagram_followers_weekly_diff_percent'] = $instagramFollower['value']->weekly_diff_percent;
                $entry['instagram_followers_monthly_diff_percent'] = $instagramFollower['value']->monthly_diff_percent;
            }
            else
            {
                $entry['instagram_followers'] = null;
                $entry['instagram_followers_diff'] = null;
                $entry['instagram_followers_weekly_diff_percent'] = null;
                $entry['instagram_followers_monthly_diff_percent'] = null;
            }

            if(isset($stats['soundcloud']['followers'][$date][$artistId]))
            {
                $soundcloudFollower = $stats['soundcloud']['followers'][$date][$artistId];
                $entry['soundcloud_followers'] = $soundcloudFollower['value']->value;
                $entry['soundcloud_followers_diff'] = $soundcloudFollower['value']->diff;
                $entry['soundcloud_followers_weekly_diff_percent'] = $soundcloudFollower['value']->weekly_diff_percent;
                $entry['soundcloud_followers_monthly_diff_percent'] = $soundcloudFollower['value']->monthly_diff_percent;
            }
            else
            {
                $entry['soundcloud_followers'] = null;
                $entry['soundcloud_followers_diff'] = null;
                $entry['soundcloud_followers_weekly_diff_percent'] = null;
                $entry['soundcloud_followers_monthly_diff_percent'] = null;
            }

            if(isset($stats['tiktok']['followers'][$date][$artistId]))
            {
                $tiktokFollower = $stats['tiktok']['followers'][$date][$artistId];
                $entry['tiktok_followers'] = $tiktokFollower['value']->value;
                $entry['tiktok_followers_diff'] = $tiktokFollower['value']->diff;
                $entry['tiktok_followers_weekly_diff_percent'] = $tiktokFollower['value']->weekly_diff_percent;
                $entry['tiktok_followers_monthly_diff_percent'] = $tiktokFollower['value']->monthly_diff_percent;
            }
            else
            {
                $entry['tiktok_followers'] = null;
                $entry['tiktok_followers_diff'] = null;
                $entry['tiktok_followers_weekly_diff_percent'] = null;
                $entry['tiktok_followers_monthly_diff_percent'] = null;
            }

            if(isset($stats['twitter']['followers'][$date][$artistId]))
            {
                $twitterFollower = $stats['twitter']['followers'][$date][$artistId];
                $entry['twitter_followers'] = $twitterFollower['value']->value;
                $entry['twitter_followers_diff'] = $twitterFollower['value']->diff;
                $entry['twitter_followers_weekly_diff_percent'] = $twitterFollower['value']->weekly_diff_percent;
                $entry['twitter_followers_monthly_diff_percent'] = $twitterFollower['value']->monthly_diff_percent;
            }
            else
            {
                $entry['twitter_followers'] = null;
                $entry['twitter_followers_diff'] = null;
                $entry['twitter_followers_weekly_diff_percent'] = null;
                $entry['twitter_followers_monthly_diff_percent'] = null;
            }
            
            if(isset($stats['youtube_channel']['subscribers'][$date][$artistId]))
            {
                $youtube_channel = $stats['youtube_channel']['subscribers'][$date][$artistId];
                $entry['youtube_subs'] = $youtube_channel['value']->value;
                $entry['youtube_subs_diff'] = $youtube_channel['value']->diff;
                $entry['youtube_subs_weekly_diff_percent'] = $youtube_channel['value']->weekly_diff_percent;
                $entry['youtube_subs_monthly_diff_percent'] = $youtube_channel['value']->monthly_diff_percent;
            }
            else
            {
                $entry['youtube_subs'] = null;
                $entry['youtube_subs_diff'] = null;
                $entry['youtube_subs_weekly_diff_percent'] = null;
                $entry['youtube_subs_monthly_diff_percent'] = null;
            }
            $streamingArray[] = $entry;
        }
        ChartmetricArtistFan::insert($streamingArray);
    }
}
