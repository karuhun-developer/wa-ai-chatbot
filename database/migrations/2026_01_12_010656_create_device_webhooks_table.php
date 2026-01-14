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
        Schema::create('device_webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Wuz\Device::class)->constrained()->cascadeOnDelete();
            $table->string('event')->comment('All,MessageReceived,MessageRead,MessageSent, etc.')->default('All');
            $table->string('url')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_webhooks');
    }
};
