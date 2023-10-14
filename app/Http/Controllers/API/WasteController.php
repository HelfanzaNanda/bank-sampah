<?php

namespace App\Http\Controllers\API;

use App\Helpers\DatatableHelper;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WasteController extends Controller
{

    public function options(Request $request)
    {
        // $term = trim($request->term);
        // $options = Waste::select("id", "name as text", "price")
        //     ->where('name', 'LIKE',  '%' . $term. '%')
        //     ->orderBy('name', 'asc')->simplePaginate(10);
        // $morePages = true;
        // $pagination_obj = json_encode($options);
        // if (empty($options->nextPageUrl())){
        //     $morePages = false;
        // }
        // $results = [
        //     "results" => $options->items(),
        //     "pagination" => [
        //         "more" => $morePages
        //     ]
        // ];
        // return response()->json($results);

        try {
            $data = Waste::query()->select("id", "name", "price", "status")->where("status", "AKTIF")->get();
            return response()->json(ResponseHelper::success(data: $data), 200);

        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);

        }
    }

    public function datatables(Request $request)
    {
        $columns = $request->get("columns", []);
        $start = $request->get("start");
		$length = $request->get("length");
		$order = $request->get("order");
		$search = $request->get("search");
        $cmd = Waste::query();

        try {
            $data = DatatableHelper::make($cmd, $columns, $start, $length, $order, $search);
            return response()->json($data);

        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);

        }
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'code' => 'required',
            'name' => 'required|unique:wastes,name',
            'price' => 'required|numeric',
            'filecode' => 'required',
            'desc' => 'required',
            // 'stock' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseHelper::warning( validations: $validator->errors(), code: 422), 422);
        }

        try {
            $params = $validator->validated();
            $filecode = $request->filecode;
            $fileId = FileHelper::searchFilecode($filecode);

            unset($params['filecode']);
            $params['file_id'] = $fileId;
            Waste::create($params);
            return response()->json(ResponseHelper::success(), 200);

        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);
        }
    }


    public function find($id)
    {
        $data = Waste::whereId($id)->with('file')->first();
        if (!$data) {
            return response()->json(ResponseHelper::warning( message: 'data not found', code: 404), 404);
        }
        return response()->json(ResponseHelper::success(data: $data), 200);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:wastes,name,'.$id,
            'price' => 'required|numeric',
            'filecode' => 'required',
            'desc' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseHelper::warning( validations: $validator->errors(), code: 422), 422);
        }

        $data = Waste::find($id);
        if (!$data) {
            return response()->json(ResponseHelper::warning( message: 'data not found', code: 404), 404);
        }


        try {
            $params = $validator->validated();
            $filecode = $request->filecode;
            $fileId = FileHelper::searchFilecode($filecode);

            unset($params['filecode']);
            $params['file_id'] = $fileId;

            $data->update($params);
            return response()->json(ResponseHelper::success(), 200);

        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);
        }
    }

    public function changeStatus($id)
    {

        $data = Waste::find($id);
        if (!$data) {
            return response()->json(ResponseHelper::warning( message: 'data not found', code: 404), 404);
        }


        try {
            $status = $data->status;
            $params = [];
            if ($status == 'AKTIF') {
                $params['status'] = 'NON AKTIF';
            }else{
                $params['status'] = 'AKTIF';
            }
            $data->update($params);
            return response()->json(ResponseHelper::success(), 200);

        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);
        }
    }

    public function delete($id)
    {
        $data = Waste::whereId($id)->first();
        if (!$data) {
            return response()->json(ResponseHelper::warning( message: 'data not found', code: 404), 404);
        }

        try {
            $data->delete();
            return response()->json(ResponseHelper::success(), 200);
        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);
        }
    }
}
