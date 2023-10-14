<?php

namespace App\Http\Controllers\API;

use App\Enum\RoleEnum;
use App\Helpers\AuthHelper;
use App\Helpers\DatatableHelper;
use App\Helpers\NumberHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Transaction;
use App\Models\Waste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "items" => "required|array|min:1",
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseHelper::warning( validations: $validator->errors(), code: 422), 422);
        }
        DB::beginTransaction();

        try {
            $items = $request->items;
            foreach ($items as $item) {
                $waste = Waste::query()->where("id", $item['waste_id'])->first();
                if ($waste) {
                    Transaction::create([
                        "user_id" => auth()->id(),
                        "waste_id" => $waste->id,
                        "qty" => $item['qty'],
                        "price" => $waste->price,
                        "total_price" => $waste->price * $item['qty'],
                    ]);
                }
            }
            DB::commit();
            return response()->json(ResponseHelper::success(), 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(ResponseHelper::error(th: $th), 500);
        }
    }

}
