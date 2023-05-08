<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CatController extends Controller
{
    public function getBreed(Request $request)
    {
        
        $client = new Client();

        $url = "https://api.thecatapi.com/v1/images/search"; 

        
        $response = $client->request('GET', $url, [
            'headers' => [
                'x-api-key' => env('API_CAT_KEY')
            ]
        ]);

        $breed = $request->query('breed_ids', 'beng'); 

        
        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);
    }

    public function getImages(Request $request)
    {

        $client = new Client();

        $url = "https://api.thedogapi.com/v1/images"; 

        $limit = $request->query('limit', 10); 
        $page = $request->query('page', 0);
        $order = $request->query('order', 'DESC');
        $sub_id = $request->query('sub_id', 'user1');
        $breed_ids = $request->query('breed_ids', '1,4,28');
        $category_ids = $request->query('category_ids', '4');
        $format = $request->query('format', 'json');
        $original_filename = $request->query('original_filename', null);
        $user_id = $request->query('user_id', null);


        $response = $client->request('GET', $url, [
            'headers' => [
                'x-api-key' => env('API_DOG_KEY')
            ]
        ]);

        
        $data = json_decode($response->getBody()->getContents(), true);

        return response()->json($data);



    }
}
