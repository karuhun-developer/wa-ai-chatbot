<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Wuz\Transaction::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Catalog\Product::class)->constrained()->cascadeOnDelete();
            $table->json('product_snapshot');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
