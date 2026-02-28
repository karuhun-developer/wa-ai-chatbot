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
        Schema::table('agent_conversations', function (Blueprint $table) {
            $table->dropIndex(['chat_jid', 'updated_at']);
            $table->dropColumn('chat_jid');
            $table->foreignIdFor(App\Models\Wuz\DeviceContact::class)->nullable()->constrained()->nullOnDelete()->after('id');
            $table->index(['updated_at']);
        });

        Schema::table('agent_conversation_messages', function (Blueprint $table) {
            $table->dropIndex('conversation_index');
            $table->dropIndex(['chat_jid']);
            $table->dropColumn('chat_jid');
            $table->foreignIdFor(App\Models\Wuz\DeviceContact::class)->nullable()->constrained()->nullOnDelete()->after('conversation_id');
            $table->index(['conversation_id', 'device_contact_id', 'updated_at'], 'conversation_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
