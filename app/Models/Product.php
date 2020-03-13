<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inid', 'quantity', 'scu', 'barcode', 'size', 'warehouse_id', 'updated_at'
    ];



    /**
     * @var array
     */
    protected $hidden = [];


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}
