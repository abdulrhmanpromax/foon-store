<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'min_quantity')) {
                $table->unsignedInteger('min_quantity')->default(1)->after('external_service_id');
            }
            if (! Schema::hasColumn('products', 'max_quantity')) {
                $table->unsignedInteger('max_quantity')->default(100000)->after('min_quantity');
            }
        });

        DB::table('products')
            ->where('delivery_type', 'api')
            ->update([
                'min_quantity' => 100,
                'max_quantity' => 60000,
            ]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['min_quantity', 'max_quantity']);
        });
    }
};
