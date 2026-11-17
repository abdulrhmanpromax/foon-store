<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'product_id',
        'target_input',
        'quantity',
        'total_price',
        'status',
        'delivery_data',
        'external_order_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function vaultItems(): HasMany
    {
        return $this->hasMany(VaultItem::class);
    }

    /**
     * Safely decrypt delivery_data for preview in Filament and UI.
     */
    public function getDecryptedPreviewAttribute(): ?string
    {
        if (empty($this->delivery_data)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->delivery_data);
        } catch (Throwable) {
            return (string) $this->delivery_data;
        }
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }
}
