<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class ArtistController extends Controller
{   
  
    public function __construct()
    {
        
    }

    public function generateToken(){
        

        $client = new Client();

        $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [

            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],

            'form_params' => [
                'grant_type' =>env("GRANT_TYPE"),
                'client_id' => env("CLIENT_ID"),
                'client_secret' => env("CLIENT_SECRET")
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            return $data['access_token'];
        } else {
            $data['code'] = 400;
            $data['message'] = "Error with the request. Please try again.";

            return $data;
        }
    }

    
    public function getTracks($artist_id)
    {
        $client = new Client();

        $url = "https://api.spotify.com/v1/artists/{$artist_id}"; // URL de la API de Spotify

        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->generateToken(), // Token de acceso de Spotify
            ]
        ]);

        
        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);
    }

    public function getAlbum($album_id)
    {
        $client = new Client();

        $url = "https://api.spotify.com/v1/albums/{$album_id}";

        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->generateToken(), // Token de acceso de Spotify
            ]
        ]);

        
        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);
    }
}
