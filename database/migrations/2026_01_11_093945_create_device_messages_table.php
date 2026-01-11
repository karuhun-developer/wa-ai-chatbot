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
        Schema::create('device_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Wuz\Device::class)->constrained()->cascadeOnDelete();
            $table->string('chat_jid')->nullable();
            $table->string('sender_jid')->nullable();
            $table->text('message')->nullable();
            $table->json('metadata')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_messages');
    }
};
