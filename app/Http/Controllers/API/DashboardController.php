<?php
namespace App\Http\Controllers\API;

use App\Helpers\DatatableHelper;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Transaction;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{


    public function datatables(Request $request)
    {
        $columns = $request->get("columns", []);
        $start = $request->get("start");
		$length = $request->get("length");
		$order = $request->get("order");
		$search = $request->get("search");
        $cmd = Transaction::query()->where("user_id", auth()->id())->with("waste");

        try {
            $data = DatatableHelper::make($cmd, $columns, $start, $length, $order, $search);
            return response()->json($data);

        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);

        }
    }

    public function chart(Request $request)
    {

        try {

            $categories = Waste::query()->select("name")->where("status", "AKTIF")->get()->pluck("name");

            $count = Waste::query()
                ->select("name", )
                ->selectRaw("(select count(*) from transactions where transactions.waste_id = wastes.id) as count")
                ->where("status", "AKTIF")->get();

            $totalYourMoney = Transaction::query()->where("user_id", auth()->id())->sum("total_price");


            $data = [
                'categories' => $categories,
                'count' => $count,
                'totalYourMoney' => $totalYourMoney,
            ];


            return response()->json(ResponseHelper::success(data: $data), 200);
        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);
        }
    }

    public function images(Request $request)
    {
        try {
            $images = File::query()->get()->pluck('fileurl');
            return response()->json(ResponseHelper::success(data: $images), 200);
        } catch (\Throwable $th) {
            return response()->json(ResponseHelper::error(th: $th), 500);
        }
    }
}
