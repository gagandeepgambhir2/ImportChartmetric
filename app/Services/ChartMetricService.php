<?php

namespace App\Services;

class ChartMetricService
{
    protected $refresh_token;
    public function __construct()
    {   
        $this->refresh_token = env('CHARTMETRIC_REFRESH_TOKEN');
    }
    
    /**
     * getAccessToken
     *
     * @return void
     */
    private function getAccessToken()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://api.chartmetric.com/api/token', [
                'form_params' => [
                    'refreshtoken' => $this->refresh_token
                ],
            ])->getBody()->getContents();

            $arr = json_decode($response, true);
            $access_token = $arr['token'];
            return $access_token;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * getChartMetricId
     *
     * @param  mixed $isrc
     * @return string
     */
    public function getChartMetricId($isrc)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://api.chartmetric.com/api/track/isrc/$isrc/get-ids", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->getAccessToken()
            ],
            'http_errors' => false
        ]);
        $content = $response->getBody()->getContents();
        return $content;
    }
    
    /**
     * get chart metric track data
     *
     * @param  mixed $chartMatrixId
     * @return string
     */
    public function getChartMetricTrackData($chartMatrixId=null)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://api.chartmetric.com/api/track/$chartMatrixId", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' .$this->getAccessToken()
            ],
            'http_errors' => false
        ]);
        $content = $response->getBody()->getContents();
        return $content;
    }

    public function getStat($artist_id = null, $source, $since=null,$until=null)
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get("https://api.chartmetric.com/api/artist/$artist_id/stat/$source?since=$since&until=$until", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' .$this->getAccessToken()
                ],
                'http_errors' => false
            ]);
            $status = $response->getStatusCode();
            if ($status != 403 && $status != 401) {
                $content = $response->getBody()->getContents();
                return collect(json_decode($content));
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getIdsBySpotify($artist_id)
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get("https://api.chartmetric.com/api/artist/spotify/$artist_id/get-ids", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' .$this->getAccessToken()
                ],
                'http_errors' => false
            ]);
            $status = $response->getStatusCode();
            if ($status != 403 && $status != 401) {
                $content = $response->getBody()->getContents();
                return collect(json_decode($content));
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getChartMetricStat($chartMatrixId)
    {
       $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get("https://api.chartmetric.com/api/artist/$chartMatrixId", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' .$this->getAccessToken()
                ],
                'http_errors' => false
            ]);
            $status = $response->getStatusCode();
            if ($status != 403 && $status != 401) {
                $content = $response->getBody()->getContents();
                return collect(json_decode($content));
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}