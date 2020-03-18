<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Log;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;


class Controller extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function product(Request $request)
    {
        $log = new Log();
        $data_json = file_get_contents("php://input");
        $validator = \Validator::make(['json'=> $data_json], ['json' => 'JSON']);
        if ($validator->fails()) {

            $log->description = $data_json;
            $log->type = 'change';
            $log->level = 'critical';
            $log->token = $request->user()->key;
            $log->save();
            return response()->json(['error' => 'Bad Request'], 400, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        $data = json_decode($data_json, true);

        $postData = $data['product'][0];

        // New warehouse or OLD
        $warehouse = new Warehouse;

        try {
            $warehouse = $warehouse->where('warehouse', $postData['warehouse'])->firstOrFail();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $warehouse->warehouse = $postData['warehouse'];
            $warehouse->save();
        }

        $warehouse_id = $warehouse->id;

        $rules = [
            'unid' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'scu' => 'required|alpha_dash',
            'barcode' => 'required|digits:13',
            'size' => 'required|min:1',
            'warehouse' => 'required|string',
        ];

        $validator = \Validator::make($postData, $rules);
        if ($validator->fails()) {
            $errorMessages = $validator->errors()->messages();
            $text = "";
            foreach ($errorMessages as $key => $value) {
                $text .= $value[0].PHP_EOL;
            }
            $log->description = $text;
            $log->type = 'change';
            $log->level = 'error';
            $log->token = $request->user()->key;
            $log->save();
            return response()->json(['error' => 'Bad Request'], 400, ['Content-Type' => 'application/json; charset=UTF-8']);
        }

        /// New Product or OLD ??
        $product = new Product;

        try {
            $product = $product::where('unid', (int)$postData['unid'])
                ->where('warehouse_id', $warehouse_id)
                ->firstOrFail();

            $quantity_old = $product::first()->quantity;
            $product->unid = (int)$postData['unid'];
            $product->warehouse_id = $warehouse_id;
            $product->quantity = $quantity_old + (int)$postData['quantity'];
            $product->scu = $postData['scu'] ?? $product->scu;
            $product->barcode = $postData['barcode'] ?? $product->barcode;
            $product->size = $postData['size'] ?? $product->size;
            $product->update();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $product->unid = (int)$postData['unid'];
            $product->warehouse_id = $warehouse_id;
            $product->scu = $postData['scu'];
            $product->quantity = (int)$postData['quantity'];
            $product->barcode = $postData['barcode'];
            $product->size = $postData['size'];
            $product->save();
        }

        $log->description = $data_json;
        $log->type = 'change';
        $log->level = 'info';
        $log->token = $request->user()->key;
        $log->save();

        return response()->json(['error' => 'no'], 201, ['Content-Type' => 'application/json; charset=UTF-8']);
    }

    public function error ()
    {
        return response()->json(['error' => 'Bad Request'], 400, ['Content-Type' => 'application/json; charset=UTF-8']);

    }
}
