<?php

namespace App\Enums\Wuz;

enum EventType: string
{
    case MESSAGE = 'Message';
    case DISCONNECTED = 'Disconnected';
    case LOGGED_OUT = 'LoggedOut';
    case UNKNOWN = 'Unknown';

    public function label(): string
    {
        return match ($this) {
            self::MESSAGE => 'Message',
            self::DISCONNECTED => 'Disconnected',
            self::LOGGED_OUT => 'Logged Out',
            self::UNKNOWN => 'Unknown',
        };
    }

    /**
     * Detect event type from webhook payload
     */
    public static function detect(array $data): self
    {
        $data = $data['event'] ?? $data;

        // Check for message event
        if (isset($data['Message']) && isset($data['Info'])) {
            return self::MESSAGE;
        }

        // Check for logged out event
        if (isset($data['Reason']) && isset($data['OnConnect'])) {
            return self::LOGGED_OUT;
        }

        // Check for explicit event_type field
        if (isset($data['event_type'])) {
            return match ($data['event_type']) {
                'Message' => self::MESSAGE,
                'Disconnected' => self::DISCONNECTED,
                'LoggedOut' => self::LOGGED_OUT,
                default => self::UNKNOWN,
            };
        }

        return self::UNKNOWN;
    }
}

