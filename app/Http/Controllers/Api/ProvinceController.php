<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProvinceModel;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    public function index()
    {
        $province = ProvinceModel::orderBy('province_id', 'ASC')->get();
        return ApiFormatter::createJson(200, 'Get Data Success', $province);
    }

    public function detail($id)
    {
        try {
            $province = ProvinceModel::find($id);
            if (is_null($province)) {
                return ApiFormatter::createJson(404, 'Province Not Found');
            }
            return ApiFormatter::createJson(200, 'Get Detail Province Success', $province);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'province_code' => 'required|max:10',
                'province_name' => 'required',
            ], [
                'province_code.required' => 'Province Code is required',
                'province_code.max' => 'Province Code must not exceed 10 characters',
                'province_name.required' => 'Province Name is required',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $province = [
                'province_code' => $params['province_code'],
                'province_name' => $params['province_name'],
            ];

            $createdProvince = ProvinceModel::create($province);
            return ApiFormatter::createJson(201, 'Create Province Success', $createdProvince);
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preProvince = ProvinceModel::find($id);
            
            if (is_null($preProvince)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            $validator = Validator::make($params, [
                'province_code' => 'required|max:10',
                'province_name' => 'required',
            ], [
                'province_code.required' => 'Province Code is required',
                'province_code.max' => 'Province Code must not exceed 10 characters',
                'province_name.required' => 'Province Name is required',
            ]);

            if ($validator->fails()) {
                return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
            }

            $preProvince->update([
                'province_code' => $params['province_code'],
                'province_name' => $params['province_name'],
            ]);

            return ApiFormatter::createJson(200, 'Update Province Success', $preProvince->fresh());
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $params = $request->all();
            $preProvince = ProvinceModel::find($id);
            
            if (is_null($preProvince)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }

            if (isset($params['province_code'])) {
                $validator = Validator::make($params, [
                    'province_code' => 'required|max:10',
                ]);
                if ($validator->fails()) {
                    return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                }
                $preProvince->province_code = $params['province_code'];
            }

            if (isset($params['province_name'])) {
                $validator = Validator::make($params, [
                    'province_name' => 'required',
                ]);
                if ($validator->fails()) {
                    return ApiFormatter::createJson(400, 'Bad Request', $validator->errors()->all());
                }
                $preProvince->province_name = $params['province_name'];
            }

            $preProvince->save();
            return ApiFormatter::createJson(200, 'Update Partial Province Success', $preProvince->fresh());
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $province = ProvinceModel::find($id);
            if (is_null($province)) {
                return ApiFormatter::createJson(404, 'Data Not Found');
            }
            $province->delete();
            return ApiFormatter::createJson(200, 'Delete Province Success');
        } catch (\Exception $e) {
            return ApiFormatter::createJson(500, 'Internal Server Error', $e->getMessage());
        }
    }
}
