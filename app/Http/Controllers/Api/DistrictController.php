<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DistrictModel;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function index()
    {
        $district = DistrictModel::orderBy('district_id', 'ASC')->get();
        return ApiFormatter::createJson(200, 'Get Data Success', $district);
    }

    public function detail($id)
    {
        try {
            $district = DistrictModel::find($id);
            if (is_null($district)) {
                return ApiFormatter::createJson(404, 'District Not Found');
            }
            return ApiFormatter::createJson(200, 'Get Detail District Success', $district);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'city_id' => 'required|integer',
                'district_code' => 'required|max:10',
                'district_name' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $createdDistrict = DistrictModel::create($params);
            return ApiFormatter::createJson(201, 'Create District Success', $createdDistrict);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preDistrict = DistrictModel::find($id);
            
            if (is_null($preDistrict)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            $validator = Validator::make($params, [
                'city_id' => 'required|integer',
                'district_code' => 'required|max:10',
                'district_name' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $preDistrict->update($params);
            return ApiFormatter::createJson(200, 'Update District Success', $preDistrict->fresh());
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preDistrict = DistrictModel::find($id);
            
            if (is_null($preDistrict)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            $preDistrict->fill($params);
            $preDistrict->save();

            return ApiFormatter::createJson(200, 'Update Partial District Success', $preDistrict->fresh());
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $district = DistrictModel::find($id);
            if (is_null($district)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }
            $district->delete();
            return ApiFormatter::createJson(200, 'Delete District Success');
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }
}
