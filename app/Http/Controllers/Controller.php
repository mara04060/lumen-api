<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;

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
        $data = json_decode(file_get_contents("php://input"), true);
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


        /// New Product or OLD ??
        $product = new Product;

        try {
            $product = $product::where('unid', (int)$postData['unid'])
                ->where('warehouse_id', $warehouse_id)
                ->firstOrFail();

            $quantity_old = $product::where('unid', (int)$postData['unid'])->first()->id;
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

        return response()->json(['error' => 'on'], 201, ['Content-Type' => 'application/json; charset=UTF-8']);
    }
}
