<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\City;
use GuzzleHttp\Client;

/**
 * CityController for fluent user CRUD
 */
class ForecastController extends Controller
{
    /**
     * Stores routed related configuration params
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor to inject dependencies
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = request('config');
    }

    /**
     * To add a new city
     *
     * @param string $name -
     *
     * @return response
     */
    public function fetch()
    {
        try {
            $data = request()->all();
            $city = City::find($data['id']);

            // api code here
            // $endpoint = 'https://api.openweathermap.org/data/2.5/weather?q=' . $city->name . '&appid=' . env('API_KEY');
            $endpoint = 'https://api.openweathermap.org/data/2.5/forecast?q=' . $city->name . '&appid=' . env('API_KEY');

            $client = new Client();

            $result = $client->request('POST', $endpoint, []);

            $response = json_decode($result->getBody()->getContents());

            $city->forecast = json_encode($response);
            $city->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Forecast fetch and save successful',
                    'data' => []
                ],
                200
            );
        } catch (\Exception $e) {
            $error = 'App\Http\Controllers\ForecastController@fetch ---> Message =>' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine();

            Log::info($error);

            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => []
                ],
                400
            );
        }
    }

    /**
     * To fetch forecast for all cities present in database
     *
     * @return response
     */
    public function fetchAll()
    {
        try {
            $cities = City::all();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Forecast fetch successful',
                    'data' => $cities
                ],
                200
            );
        } catch (\Exception $e) {
            $error = 'App\Http\Controllers\ForecastController@fetchAll ---> Message =>' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine();

            Log::info($error);

            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => []
                ],
                400
            );
        }
    }
}
