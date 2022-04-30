<?php

namespace App\Models;

use App\Models\Traits\Relationships\InvoiceRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    use InvoiceRelationship;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
        'invoice_number',
        'date',
        'customer_name',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'products',
    ];
}
