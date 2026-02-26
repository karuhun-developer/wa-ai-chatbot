<?php

use App\Ai\Agents\WuzAgent;
use App\Jobs\Wuz\HandleAiReplyJob;
use App\Models\Setting\Setting;
use App\Models\User;
use App\Models\Wuz\Device;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->device = Device::create([
        'user_id' => $this->user->id,
        'device_id' => '12345',
        'name' => 'Test Device AI',
        'token' => 'test-token-ai',
        'connected' => true,
        'jid' => '628123456789@s.whatsapp.net',
        'ai_enabled' => true,
    ]);

    // Create fake setting
    Setting::create([
        'value' => [
            'system_prompt' => 'You are a test assistant',
        ]
    ]);
});

test('it handles text message using AI agent when ai_enabled is true', function () {
    Queue::fake();

    $payload = [
        'Info' => [
            'RemoteJid' => '628987654321@s.whatsapp.net',
            'Sender' => [
                'User' => '628987654321',
            ],
            'Id' => 'MSG123_AI',
        ],
        'Message' => [
            'conversation' => 'Hello Bot',
        ],
        'event_type' => 'Message',
    ];

    $response = $this->postJson("/api/v1/webhook/{$this->device->token}", $payload);

    $response->assertStatus(200);

    Queue::assertPushed(HandleAiReplyJob::class, function (HandleAiReplyJob $job) {
        return $job->deviceId === $this->device->id
            && $job->phone === '628987654321'
            && $job->messageContent === 'Hello Bot';
    });
});

test('it does not use AI agent when ai_enabled is false', function () {
    Queue::fake();

    $device = Device::create([
        'user_id' => $this->user->id,
        'device_id' => '123456',
        'name' => 'Test Device No AI',
        'token' => 'test-token-no-ai',
        'connected' => true,
        'jid' => '6281234567890@s.whatsapp.net',
        'ai_enabled' => false,
    ]);

    $payload = [
        'Info' => [
            'RemoteJid' => '628987654321@s.whatsapp.net',
            'Sender' => [
                'User' => '628987654321',
            ],
            'Id' => 'MSG123_NO_AI',
        ],
        'Message' => [
            'conversation' => 'Hello Bot',
        ],
        'event_type' => 'Message',
    ];

    $response = $this->postJson("/api/v1/webhook/{$device->token}", $payload);

    $response->assertStatus(200);

    Queue::assertNotPushed(HandleAiReplyJob::class);
});

test('HandleAiReplyJob triggers agent and dispatches reply', function () {
    WuzAgent::fake(['This is an AI response']);
    Queue::fake([\App\Jobs\Wuz\SendWuzMessageJob::class]);

    $job = new HandleAiReplyJob($this->device->id, '628987654321', 'Hello test');
    $job->handle();

    WuzAgent::assertPrompted('Hello test');

    Queue::assertPushed(\App\Jobs\Wuz\SendWuzMessageJob::class, function ($job) {
        return $job->phone === '628987654321'
            && $job->type === 'text'
            && $job->messageContent === 'This is an AI response';
    });
});
