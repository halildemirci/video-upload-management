<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Video extends Model
{
    protected $fillable = [
        'title',
        'owner',
        'category',
        'country',
        'city',
        'video_path',
        'lat',
        'lng'
    ];

    public function setCoordinates()
    {
        $location = "{$this->city}, {$this->country}";
        $apiKey = env('GEOCODING_API_KEY');

        $response = Http::get("https://api.opencagedata.com/geocode/v1/json", [
            'q' => $location,
            'key' => $apiKey
        ]);

        $data = $response->json();

        if (!empty($data['results'])) {
            $this->lat = $data['results'][0]['geometry']['lat'];
            $this->lng = $data['results'][0]['geometry']['lng'];
            $this->save();
        }
    }
}
