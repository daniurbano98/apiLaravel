<?php

namespace App\Http\Controllers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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

    
    public function getArtist(Request $request, $artist_id)
    {
        $client = new Client();

        $url = "https://api.spotify.com/v1/artists/{$artist_id}"; // URL de la API de Spotify

        $token = $request->bearerToken();

        
        if($this->auhtenticate( $token )){
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->generateToken(), // Token de acceso de Spotify
                ]
            ]);

            
            $data = json_decode($response->getBody()->getContents(), true);
        
            return response()->json($data);
        }else{
            return response()->json(['error' => 'Token inválido'], 401);
        }  
    }

    public function getAlbum(Request $request, $album_id)
    {
        $client = new Client();

        $url = "https://api.spotify.com/v1/albums/{$album_id}";

        $token = $request->bearerToken();

        if($this->auhtenticate( $token )){
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->generateToken(), // Token de acceso de Spotify
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json($data);
        }else{
            return response()->json(['error' => 'Token inválido'], 401);
        }  
    }

    public function auhtenticate($token)
    {
        try{
            $jwt = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            return true;

        }catch(\Exception $e){
            return false;
        }
    }
}
