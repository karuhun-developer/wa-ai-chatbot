<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WuzService
{
    private PendingRequest $httpClient;

    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $userToken = null,
        public ?string $adminToken = null,
        public ?string $apiUrl = null,
    ) {
        // If no user token is provided, use the default from config
        if (is_null($this->userToken)) {
            $this->userToken = config('wuz.user_token');
        }

        // If no admin token is provided, use the default from config
        if (is_null($this->adminToken)) {
            $this->adminToken = config('wuz.admin_token');
        }

        // If no API URL is provided, use the default from config
        if (is_null($this->apiUrl)) {
            $this->apiUrl = config('wuz.api_url');
        }

        // Set up the HTTP client with default headers
        $this->httpClient = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $this->adminToken,
            'token' => $this->userToken,
        ]);
    }

    /**
     * Fetch a list of users from the Wuz API.
     */
    public function listUsers()
    {
        $response = $this->httpClient->get($this->apiUrl.'/admin/users');

        if ($response->failed()) {
            Log::error('Wuz list users error: '.$response->body());
            throw new Exception('Failed to fetch users: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Add a new user via the Wuz API.
     */
    public function addUser(string $name, string $token, bool $history = false)
    {
        $response = $this->httpClient->post($this->apiUrl.'/admin/users', [
            'name' => $name,
            'token' => $token,
            'events' => 'All',
            'webhook' => url('api/v1/webhook/'.$token),
            'history' => $history ? 1 : 0,
        ]);

        if ($response->failed()) {
            Log::error('Wuz add user error: '.$response->body());
            throw new Exception('Failed to add user: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Show details of a specific user by id.
     */
    public function showUser(string $id)
    {
        $response = $this->httpClient->get($this->apiUrl.'/admin/users/'.$id);

        if ($response->failed()) {
            Log::error('Wuz show user error: '.$response->body());
            throw new Exception('Failed to fetch user: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Delete a user by id.
     */
    public function deleteUser(string $id)
    {
        $response = $this->httpClient->delete($this->apiUrl.'/admin/users/'.$id.'/full');

        if ($response->failed()) {
            Log::error('Wuz delete user error: '.$response->body());
            throw new Exception('Failed to delete user: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Session connect
     */
    public function sessionConnect()
    {
        $response = $this->httpClient->post($this->apiUrl.'/session/connect', [
            'Immediate' => true,
        ]);

        if ($response->failed()) {
            Log::error('Wuz session connect error: '.$response->body());
            throw new Exception('Failed to connect session: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Session disconnect
     */
    public function sessionDisconnect()
    {
        $response = $this->httpClient->post($this->apiUrl.'/session/disconnect');

        if ($response->failed()) {
            Log::error('Wuz session disconnect error: '.$response->body());
            throw new Exception('Failed to disconnect session: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Session logout
     */
    public function sessionLogout()
    {
        $response = $this->httpClient->post($this->apiUrl.'/session/logout');

        if ($response->failed()) {
            Log::error('Wuz session logout error: '.$response->body());
            throw new Exception('Failed to logout session: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Get session status
     */
    public function sessionStatus()
    {
        $response = $this->httpClient->get($this->apiUrl.'/session/status');

        if ($response->failed()) {
            Log::error('Wuz session status error: '.$response->body());
            throw new Exception('Failed to get session status: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Get session qr
     */
    public function sessionQr()
    {
        $response = $this->httpClient->get($this->apiUrl.'/session/qr');

        if ($response->failed()) {
            throw new Exception('Failed to get session QR: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Phone to jid lid
     */
    public function phoneToJid(string $phone)
    {
        $response = $this->httpClient->get($this->apiUrl.'/user/lid/'.$phone);

        if ($response->failed()) {
            Log::error('Wuz phone to jid error: '.$response->body());
            throw new Exception('Failed to get JID: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Check phone is registered
     */
    public function isPhoneRegistered(string $phone)
    {
        $response = $this->httpClient->post($this->apiUrl.'/user/check/', [
            'Phone' => [
                $phone,
            ],
        ]);

        if ($response->failed()) {
            Log::error('Wuz is phone registered error: '.$response->body());
            throw new Exception('Failed to check phone registration: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Set Proxy
     */
    public function setProxy(string $proxyUrl, bool $enable = true)
    {
        $response = $this->httpClient->post($this->apiUrl.'/session/proxy', [
            'proxy_url' => $proxyUrl,
            'enable' => $enable,
        ]);

        if ($response->failed()) {
            Log::error('Wuz set proxy error: '.$response->body());
            throw new Exception('Failed to set proxy: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Send message text
     */
    public function sendMessageText(string $to, string $message, ?bool $linkPreview = false)
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/send/text', [
            'Phone' => $to,
            'Body' => $message,
            'Id' => uniqid().time(),
            'LinkPreview' => $linkPreview,
        ]);

        if ($response->failed()) {
            Log::error('Wuz send message text error: '.$response->body());
            throw new Exception('Failed to send message: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Mark message as read
     */
    public function markMessageAsRead(string $messageId, string $chatPhone, string $senderPhone)
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/markread', [
            'Id' => [
                $messageId,
            ],
            'ChatPhone' => $chatPhone,
            'SenderPhone' => $senderPhone,
        ]);

        if ($response->failed()) {
            Log::error('Wuz mark message as read error: '.$response->body());
            throw new Exception('Failed to mark message as read: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Send chat presence
     * type = 'composing' | 'paused'
     * media = '' | 'audio'
     */
    public function sendChatPresence(string $to, string $type = 'composing', string $media = '')
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/presence', [
            'Phone' => $to,
            'State' => $type,
            'Media' => $media,
        ]);

        if ($response->failed()) {
            Log::error('Wuz send chat presence error: '.$response->body());
            throw new Exception('Failed to send chat presence: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Send message image
     */
    public function sendMessageImage(string $to, string $base64Image, ?string $caption = '')
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/send/image', [
            'Phone' => $to,
            'Image' => $base64Image,
            'Caption' => $caption,
            'Id' => uniqid().time(),
        ]);

        if ($response->failed()) {
            Log::error('Wuz send message image error: '.$response->body());
            throw new Exception('Failed to send image message: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Send message document
     */
    public function sendMessageDocument(string $to, string $base64Document, string $filename)
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/send/document', [
            'Phone' => $to,
            'Document' => $base64Document,
            'FileName' => $filename,
            'Id' => uniqid().time(),
        ]);

        if ($response->failed()) {
            Log::error('Wuz send message document error: '.$response->body());
            throw new Exception('Failed to send document message: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Send message video
     */
    public function sendMessageVideo(string $to, string $base64Video, ?string $caption = '')
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/send/video', [
            'Phone' => $to,
            'Video' => $base64Video,
            'Caption' => $caption,
            'Id' => uniqid().time(),
        ]);

        if ($response->failed()) {
            Log::error('Wuz send message video error: '.$response->body());
            throw new Exception('Failed to send video message: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Send message button
     */
    public function sendMessageButton(string $to, string $message, array $buttons)
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/send/buttons', [
            'Phone' => $to,
            'Body' => $message,
            'Footer' => 'Powered by Voda',
            'Buttons' => $buttons,
            'Id' => uniqid().time(),
        ]);

        if ($response->failed()) {
            Log::error('Wuz send message button error: '.$response->body());
            throw new Exception('Failed to send button message: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Download image
     */
    public function downloadImage(string $url, string $directPath, string $mediaKey, string $mimetype, string $fileEncSHA256, string $fileSHA256, int $fileLength)
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/downloadimage', [
            'Url' => $url,
            'DirectPath' => $directPath,
            'MediaKey' => $mediaKey,
            'Mimetype' => $mimetype,
            'FileEncSHA256' => $fileEncSHA256,
            'FileSHA256' => $fileSHA256,
            'FileLength' => $fileLength,
        ]);

        if ($response->failed()) {
            Log::error('Wuz download image error: '.$response->body());
            throw new Exception('Failed to download image: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Download document
     */
    public function downloadDocument(string $url, string $directPath, string $mediaKey, string $mimetype, string $fileEncSHA256, string $fileSHA256, int $fileLength)
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/downloaddocument', [
            'Url' => $url,
            'DirectPath' => $directPath,
            'MediaKey' => $mediaKey,
            'Mimetype' => $mimetype,
            'FileEncSHA256' => $fileEncSHA256,
            'FileSHA256' => $fileSHA256,
            'FileLength' => $fileLength,
        ]);

        if ($response->failed()) {
            Log::error('Wuz download document error: '.$response->body());
            throw new Exception('Failed to download document: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Download video
     */
    public function downloadVideo(string $url, string $directPath, string $mediaKey, string $mimetype, string $fileEncSHA256, string $fileSHA256, int $fileLength)
    {
        $response = $this->httpClient->post($this->apiUrl.'/chat/downloadvideo', [
            'Url' => $url,
            'DirectPath' => $directPath,
            'MediaKey' => $mediaKey,
            'Mimetype' => $mimetype,
            'FileEncSHA256' => $fileEncSHA256,
            'FileSHA256' => $fileSHA256,
            'FileLength' => $fileLength,
        ]);

        if ($response->failed()) {
            Log::error('Wuz download video error: '.$response->body());
            throw new Exception('Failed to download video: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Set webhook events, sometimes needed to update events
     */
    public function setWebhookEvents(string $token)
    {
        $availableEvents = [
            'Message', 'UndecryptableMessage', 'Receipt', 'MediaRetry', 'GroupInfo',
            'JoinedGroup', 'Picture', 'BlocklistChange', 'Blocklist', 'Connected',
            'Disconnected', 'ConnectFailure', 'KeepAliveRestored', 'KeepAliveTimeout',
            'LoggedOut', 'ClientOutdated', 'TemporaryBan', 'StreamError', 'StreamReplaced',
            'PairSuccess', 'PairError', 'QR', 'QRScannedWithoutMultidevice', 'PrivacySettings',
            'PushNameSetting', 'UserAbout', 'AppState', 'AppStateSyncComplete', 'HistorySync',
            'OfflineSyncCompleted', 'OfflineSyncPreview', 'CallOffer', 'CallAccept',
            'CallTerminate', 'CallOfferNotice', 'CallRelayLatency', 'Presence', 'ChatPresence',
            'IdentityChange', 'CATRefreshError', 'NewsletterJoin', 'NewsletterLeave',
            'NewsletterMuteChange', 'NewsletterLiveUpdate', 'FBMessage', 'All',
        ];

        $response = $this->httpClient->put($this->apiUrl.'/webhook', [
            'webhook' => url('api/v1/webhook/'.$token),
            'events' => [
                'Message',
                'UndecryptableMessage',
                'Connected',
                'ConnectFailure',
                'Disconnected',
                'LoggedOut',
                'TemporaryBan',
            ],
            'Active' => true,
        ]);

        if ($response->failed()) {
            Log::error('Wuz set webhook events error: '.$response->body());
            throw new Exception('Failed to set webhook events: '.$response->body());
        }

        return $response->json();
    }
}
