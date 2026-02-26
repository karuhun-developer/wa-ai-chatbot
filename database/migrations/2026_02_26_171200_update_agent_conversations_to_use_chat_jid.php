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
            $table->dropIndex(['user_id', 'updated_at']);
            $table->dropColumn('user_id');
            $table->string('chat_jid')->nullable()->after('id');
            $table->index(['chat_jid', 'updated_at']);
        });

        Schema::table('agent_conversation_messages', function (Blueprint $table) {
            $table->dropIndex('conversation_index');
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
            $table->string('chat_jid')->nullable()->after('conversation_id');
            $table->index(['conversation_id', 'chat_jid', 'updated_at'], 'conversation_index');
            $table->index(['chat_jid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_conversations', function (Blueprint $table) {
            $table->dropIndex(['chat_jid', 'updated_at']);
            $table->dropColumn('chat_jid');
            $table->foreignId('user_id')->nullable()->after('id');
            $table->index(['user_id', 'updated_at']);
        });

        Schema::table('agent_conversation_messages', function (Blueprint $table) {
            $table->dropIndex('conversation_index');
            $table->dropIndex(['chat_jid']);
            $table->dropColumn('chat_jid');
            $table->foreignId('user_id')->nullable()->after('conversation_id');
            $table->index(['conversation_id', 'user_id', 'updated_at'], 'conversation_index');
            $table->index(['user_id']);
        });
    }
};
