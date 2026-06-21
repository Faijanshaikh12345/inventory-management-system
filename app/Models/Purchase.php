<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'purchase_date' => 'date',
        'total_amount'  => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Auto-generate a unique invoice number: PUR-YYYYMMDD-XXXX
     */
    public static function generateInvoiceNo(): string
    {
        $prefix = 'PUR-' . date('Ymd') . '-';
        $last   = self::where('invoice_no', 'like', $prefix . '%')
                      ->orderBy('id', 'desc')
                      ->first();

        $next = $last
            ? (int) substr($last->invoice_no, -4) + 1
            : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'received'  => '<span class="badge badge-success">Received</span>',
            'pending'   => '<span class="badge badge-warning">Pending</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default     => '<span class="badge badge-secondary">' . $this->status . '</span>',
        };
    }
}