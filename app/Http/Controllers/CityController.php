<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\City;

/**
 * CityController for fluent user CRUD
 */
class CityController extends Controller
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
     * Index or entry point of this controller
     */
    public function index()
    {
        return view($this->config['view']);
    }

    /**
     * Get cities
     */
    public function getAll()
    {
        $cities = City::all();

        return response()->json(
            [
                'success' => true,
                'message' => 'Fetched',
                'data' => $cities
            ]
        );
    }

    /**
     * To add a new city
     *
     * @return response
     */
    public function store()
    {
        $data = request()->all();

        $data['name'] = strtolower($data['name']);

        $validator = Validator::make(
            $data,
            [
                'name' => 'required|string|unique:cities,name'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ],
                400
            );
        }

        try {
            $city = new City;

            $city->name = $data['name'];
            $city->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Added city into database',
                    'data' => []
                ],
                200
            );
        } catch (\Exception $e) {
            $error = 'App\Http\Controllers\CityController@addCity ---> Message =>' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine();

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
     * To remove the existing city
     *
     * @return response
     */
    public function destroy($name)
    {
        $city = City::where('name', $name)->first();

        if ($city->count() == 0) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation failed! city not found',
                    'errors' => []
                ],
                400
            );
        }

        try {
            $city->delete();
        } catch (\Exception $e) {
            $error = 'App\Http\Controllers\CityController@destroy ---> Message =>' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine();

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
