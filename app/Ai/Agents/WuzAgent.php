<?php

namespace App\Ai\Agents;

use App\Models\Setting\Setting;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;

#[Provider('openrouter')]
#[Temperature(0.7)]
#[Model('deepseek/deepseek-chat-v3.1')]
class WuzAgent implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    public function instructions(): string
    {
        $setting = Setting::first();

        return $setting?->value['system_prompt'] ?? 'You are a helpful assistant.';
    }
}
