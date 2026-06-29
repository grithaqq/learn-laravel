<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CityModel;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        $city = CityModel::orderBy('city_id', 'ASC')->get();
        return ApiFormatter::createJson(200, 'Get Data Success', $city);
    }

    public function detail($id)
    {
        try {
            $city = CityModel::find($id);
            if (is_null($city)) {
                return ApiFormatter::createJson(404, 'City Not Found');
            }
            return ApiFormatter::createJson(200, 'Get Detail City Success', $city);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'province_id' => 'required|integer',
                'city_code' => 'required|max:10',
                'city_name' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $createdCity = CityModel::create($params);
            return ApiFormatter::createJson(201, 'Create City Success', $createdCity);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preCity = CityModel::find($id);
            
            if (is_null($preCity)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            $validator = Validator::make($params, [
                'province_id' => 'required|integer',
                'city_code' => 'required|max:10',
                'city_name' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $preCity->update($params);
            return ApiFormatter::createJson(200, 'Update City Success', $preCity->fresh());
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preCity = CityModel::find($id);
            
            if (is_null($preCity)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            // Using fill to partially update based on fillable fields
            $preCity->fill($params);
            $preCity->save();

            return ApiFormatter::createJson(200, 'Update Partial City Success', $preCity->fresh());
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $city = CityModel::find($id);
            if (is_null($city)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }
            $city->delete();
            return ApiFormatter::createJson(200, 'Delete City Success');
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }
}
