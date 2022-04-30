<?php

namespace App\Models\Traits\Relationships;

use App\Models\InvoiceProduct;

/**
 * Class InvoiceRelationship.
 */
trait InvoiceRelationship
{
    /**
     * @return hasMany
     */
    public function products()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
