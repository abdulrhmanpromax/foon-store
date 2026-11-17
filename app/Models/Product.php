<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'delivery_type',
        'price',
        'reseller_price',
        'api_provider_id',
        'external_service_id',
        'min_quantity',
        'max_quantity',
        'is_active',
    ];

    public function apiProvider(): BelongsTo
    {
        return $this->belongsTo(ApiProvider::class);
    }

    public function vaultItems(): HasMany
    {
        return $this->hasMany(VaultItem::class);
    }

    public function availableVaultItems(): HasMany
    {
        return $this->hasMany(VaultItem::class)->where('status', 'available');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isVault(): bool
    {
        return $this->delivery_type === 'vault';
    }

    public function isApi(): bool
    {
        return $this->delivery_type === 'api';
    }

    /**
     * Calculate total price (per 1,000 for API/SMM services, per unit for Vault products).
     */
    public function calculateTotalPrice(User $user, int $quantity): string
    {
        $basePrice = (string) ($user->isReseller() ? $this->reseller_price : $this->price);

        if ($this->isApi()) {
            $raw = ((float) $basePrice * $quantity) / 1000;
            $rounded = $raw > 0 ? max(0.01, round($raw, 2)) : 0.00;

            return number_format($rounded, 2, '.', '');
        }

        return bcmul($basePrice, (string) $quantity, 2);
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'reseller_price' => 'decimal:2',
            'min_quantity' => 'integer',
            'max_quantity' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
