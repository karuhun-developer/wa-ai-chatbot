<?php

use App\Enums\Wuz\EventType;
use App\Models\User;
use App\Models\Wuz\CallbackLog;
use App\Models\Wuz\Device;
use App\Models\Wuz\DeviceMessage;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->device = Device::create([
        'user_id' => $this->user->id,
        'device_id' => '12345',
        'name' => 'Test Device',
        'token' => 'test-token',
        'connected' => true,
        'jid' => '628123456789@s.whatsapp.net',
    ]);
});

test('it returns 404 for invalid token', function () {
    $response = $this->postJson('/api/v1/webhook/invalid-token', []);

    $response->assertStatus(404)
        ->assertJson([
            'code' => 404,
            'message' => 'Invalid token',
        ]);
});

test('it logs callback and handles text message event', function () {
    $payload = [
        'Info' => [
            'RemoteJid' => '628987654321@s.whatsapp.net',
            'Sender' => [
                'User' => '628987654321',
            ],
            'Id' => 'MSG123',
        ],
        'Message' => [
            'conversation' => 'Hello World',
        ],
        'event_type' => 'Message',
    ];

    $response = $this->postJson("/api/v1/webhook/{$this->device->token}", $payload);

    $response->assertStatus(200)
        ->assertJson([
            'code' => 200,
            'message' => 'Success',
            'data' => 'Callback received successfully',
        ]);

    // Verify Log
    $log = CallbackLog::first();
    expect($log)->not->toBeNull()
        ->and($log->device_id)->toBe($this->device->id)
        ->and($log->event_type)->toBe(EventType::MESSAGE->value)
        ->and($log->payload)->toBe($payload);

    // Verify Message stored
    $message = DeviceMessage::first();
    expect($message)->not->toBeNull()
        ->and($message->device_id)->toBe($this->device->id)
        ->and($message->chat_jid)->toBe('628987654321@s.whatsapp.net')
        ->and($message->sender_jid)->toBe('628987654321')
        ->and($message->message)->toBe('Hello World')
        ->and($message->type)->toBe('text');
});

test('it handles image message and downloads media', function () {
    config()->set('wuz.download_media', true);
    \Illuminate\Support\Facades\Storage::fake('public');

    Http::fake([
        '*' => Http::response([
            'code' => 200,
            'data' => [
                'Data' => 'data:image/jpeg;base64,'.base64_encode('fake-image-content'),
            ],
        ]),
    ]);

    $payload = [
        'Info' => [
            'RemoteJid' => '628987654321@s.whatsapp.net',
            'Sender' => ['User' => '628987654321'],
        ],
        'Message' => [
            'imageMessage' => [
                'caption' => 'Nice Photo',
                'url' => 'https://example.com/image.jpg',
                'directPath' => '/path/to/image',
                'mediaKey' => 'base64key',
                'mimetype' => 'image/jpeg',
                'fileEncSha256' => 'hash1',
                'fileSha256' => 'hash2',
                'fileLength' => 1024,
            ],
        ],
        'event_type' => 'Message',
    ];

    $response = $this->postJson("/api/v1/webhook/{$this->device->token}", $payload);

    $response->assertStatus(200);

    // Verify Message stored
    $message = DeviceMessage::first();
    expect($message)->not->toBeNull()
        ->and($message->type)->toBe('image')
        ->and($message->message)->toBe('Nice Photo')
        ->and($message->metadata['downloaded'])->toBeTrue()
        ->and($message->metadata['path'])->not->toBeNull()
        ->and($message->metadata['url'])->not->toBeNull();

    // Verify file exists
    \Illuminate\Support\Facades\Storage::disk('public')->assertExists($message->metadata['path']);
});

test('it does not download media when config is disabled', function () {
    config()->set('wuz.download_media', false);
    Http::fake();

    $payload = [
        'Info' => [
            'RemoteJid' => '628987654321@s.whatsapp.net',
            'Sender' => ['User' => '628987654321'],
        ],
        'Message' => [
            'imageMessage' => [
                'caption' => 'Nice Photo',
                'url' => 'https://example.com/image.jpg',
                'directPath' => '/path/to/image',
                'mediaKey' => 'base64key',
                'mimetype' => 'image/jpeg',
                'fileEncSha256' => 'hash1',
                'fileSha256' => 'hash2',
                'fileLength' => 1024,
            ],
        ],
        'event_type' => 'Message',
    ];

    $response = $this->postJson("/api/v1/webhook/{$this->device->token}", $payload);

    $response->assertStatus(200);

    // Verify Message stored but download bypassed
    $message = DeviceMessage::first();
    expect($message)->not->toBeNull()
        ->and($message->type)->toBe('image')
        ->and($message->metadata['downloaded'])->toBeFalse()
        ->and($message->metadata['error'])->toBe('Media download disabled in configuration');

    Http::assertNothingSent();
});

test('it handles disconnected event', function () {
    $payload = [
        'event_type' => 'Disconnected',
    ];

    $response = $this->postJson("/api/v1/webhook/{$this->device->token}", $payload);

    $response->assertStatus(200);

    $this->device->refresh();
    expect($this->device->connected)->toBeFalse();

    // Check log
    $log = CallbackLog::where('event_type', EventType::DISCONNECTED->value)->first();
    expect($log)->not->toBeNull();
});

test('it handles logged out event', function () {
    $payload = [
        'event_type' => 'LoggedOut',
        'Reason' => 'Logged out from phone',
        'OnConnect' => false,
    ];

    $response = $this->postJson("/api/v1/webhook/{$this->device->token}", $payload);

    $response->assertStatus(200);

    $this->device->refresh();
    expect($this->device->connected)->toBeFalse()
        ->and($this->device->jid)->toBeNull();

    // Check log
    $log = CallbackLog::where('event_type', EventType::LOGGED_OUT->value)->first();
    expect($log)->not->toBeNull();
});
