<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class CatController extends Controller
{

    public function getBreeds(Request $request)
    {
        
        $client = new Client();

        $url = "https://api.thecatapi.com/v1/breeds"; 


        $query = [];

        $token = $request->bearerToken();

        
        if($this->auhtenticate( $token )){

            if ($request->has('limit')) {
                $limit = $request->query('limit');
                $query['limit'] = $limit;
            }
    
            if ($request->has('page')) {
                $page = $request->query('page');
                $query['page'] = $page;
            }
        
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-key' => env('API_CAT_KEY')
                ],
                'query' => $query
                
            ]);
        
            $data = json_decode($response->getBody()->getContents(), true);
        
            return response()->json($data);

        }else{
            return response()->json(['error' => 'Token inválido'], 401);
        }  
    }

    public function getImages(Request $request)
    {

        $client = new Client();

        $url = "https://api.thecatapi.com/v1/images/search"; 

        $query = [];

        $token = $request->bearerToken();

        
        if($this->auhtenticate( $token )){
            if ($request->has('size')) {
                $size = $request->query('size');
                $query['size'] = $size;
            }

            if ($request->has('order')) {
                $order = $request->query('order');
                $query['order'] = $order;
            }

            if ($request->has('limit')) {
                $limit = $request->query('limit');
                $query['limit'] = $limit;
            }
        
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-key' => env('API_CAT_KEY')
                ],
                'query' => $query
                
            ]);
        }
        
        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);


    }

    public function getImage(Request $request, $id)
    {

        $client = new Client();

        $url = "https://api.thecatapi.com/v1/images/{$id}"; 

        $query = [];

        $token = $request->bearerToken();

        
        if($this->auhtenticate( $token )){

            if ($request->has('size')) {
                $size = $request->query('size');
                $query['size'] = $size;
            }

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-key' => env('API_CAT_KEY')
                ],
                'query' => $query
                
            ]);
        }
        
        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);
    }


    public function getSources(Request $request)
    {
        $client = new Client();

        $url = "https://api.thecatapi.com/v1/sources"; 

        $query = [];

        $token = $request->bearerToken();

        
        if($this->auhtenticate( $token )){
            if ($request->has('limit')) {
                $limit = $request->query('limit');
                $query['limit'] = $limit;
            }

            if ($request->has('page')) {
                $page = $request->query('page');
                $query['page'] = $page;
            }

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-key' => env('API_CAT_KEY')
                ],
                'query' => $query
                
            ]);
        }
        
        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);
    }

    public function uploadImage(Request $request) //POST
    {

        $client = new Client();

        $url = "https://api.thecatapi.com/v1/images/upload";
        $file = $request->file('image'); 


        $token = $request->bearerToken();

        
        if($this->auhtenticate( $token )){

            $response = $client->request('POST', $url, [
                'headers' => [      
                    'x-api-key' => env('API_CAT_KEY')
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => file_get_contents($file->getPathname()),
                        'filename' => $file->getClientOriginalName()
                    ]
                ]
                
            ]);
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);
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
