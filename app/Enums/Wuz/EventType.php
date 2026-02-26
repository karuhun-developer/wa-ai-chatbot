<?php

namespace App\Enums\Wuz;

enum EventType: string
{
    case MESSAGE = 'Message';
    case UNDECRYPTABLE_MESSAGE = 'UndecryptableMessage';
    case RECEIPT = 'Receipt';
    case READ_RECEIPT = 'ReadReceipt';
    case MEDIA_RETRY = 'MediaRetry';
    case GROUP_INFO = 'GroupInfo';
    case JOINED_GROUP = 'JoinedGroup';
    case PICTURE = 'Picture';
    case BLOCKLIST_CHANGE = 'BlocklistChange';
    case BLOCKLIST = 'Blocklist';
    case CONNECTED = 'Connected';
    case DISCONNECTED = 'Disconnected';
    case CONNECT_FAILURE = 'ConnectFailure';
    case KEEP_ALIVE_RESTORED = 'KeepAliveRestored';
    case KEEP_ALIVE_TIMEOUT = 'KeepAliveTimeout';
    case LOGGED_OUT = 'LoggedOut';
    case CLIENT_OUTDATED = 'ClientOutdated';
    case TEMPORARY_BAN = 'TemporaryBan';
    case STREAM_ERROR = 'StreamError';
    case STREAM_REPLACED = 'StreamReplaced';
    case PAIR_SUCCESS = 'PairSuccess';
    case PAIR_ERROR = 'PairError';
    case QR = 'QR';
    case QR_SCANNED_WITHOUT_MULTIDEVICE = 'QRScannedWithoutMultidevice';
    case PRIVACY_SETTINGS = 'PrivacySettings';
    case PUSH_NAME_SETTING = 'PushNameSetting';
    case USER_ABOUT = 'UserAbout';
    case APP_STATE = 'AppState';
    case APP_STATE_SYNC_COMPLETE = 'AppStateSyncComplete';
    case HISTORY_SYNC = 'HistorySync';
    case OFFLINE_SYNC_COMPLETED = 'OfflineSyncCompleted';
    case OFFLINE_SYNC_PREVIEW = 'OfflineSyncPreview';
    case CALL_OFFER = 'CallOffer';
    case CALL_ACCEPT = 'CallAccept';
    case CALL_TERMINATE = 'CallTerminate';
    case CALL_OFFER_NOTICE = 'CallOfferNotice';
    case CALL_RELAY_LATENCY = 'CallRelayLatency';
    case PRESENCE = 'Presence';
    case CHAT_PRESENCE = 'ChatPresence';
    case IDENTITY_CHANGE = 'IdentityChange';
    case CAT_REFRESH_ERROR = 'CATRefreshError';
    case NEWSLETTER_JOIN = 'NewsletterJoin';
    case NEWSLETTER_LEAVE = 'NewsletterLeave';
    case NEWSLETTER_MUTE_CHANGE = 'NewsletterMuteChange';
    case NEWSLETTER_LIVE_UPDATE = 'NewsletterLiveUpdate';
    case FB_MESSAGE = 'FBMessage';
    case ALL = 'All';
    case UNKNOWN = 'Unknown';

    public function label(): string
    {
        return match ($this) {
            self::MESSAGE => 'Message',
            self::UNDECRYPTABLE_MESSAGE => 'Undecryptable Message',
            self::RECEIPT => 'Receipt',
            self::READ_RECEIPT => 'Read Receipt',
            self::MEDIA_RETRY => 'Media Retry',
            self::GROUP_INFO => 'Group Info',
            self::JOINED_GROUP => 'Joined Group',
            self::PICTURE => 'Picture',
            self::BLOCKLIST_CHANGE => 'Blocklist Change',
            self::BLOCKLIST => 'Blocklist',
            self::CONNECTED => 'Connected',
            self::DISCONNECTED => 'Disconnected',
            self::CONNECT_FAILURE => 'Connection Failure',
            self::KEEP_ALIVE_RESTORED => 'Keep Alive Restored',
            self::KEEP_ALIVE_TIMEOUT => 'Keep Alive Timeout',
            self::LOGGED_OUT => 'Logged Out',
            self::CLIENT_OUTDATED => 'Client Outdated',
            self::TEMPORARY_BAN => 'Temporary Ban',
            self::STREAM_ERROR => 'Stream Error',
            self::STREAM_REPLACED => 'Stream Replaced',
            self::PAIR_SUCCESS => 'Pair Success',
            self::PAIR_ERROR => 'Pair Error',
            self::QR => 'QR Code',
            self::QR_SCANNED_WITHOUT_MULTIDEVICE => 'QR Scanned (No Multi-Device)',
            self::PRIVACY_SETTINGS => 'Privacy Settings',
            self::PUSH_NAME_SETTING => 'Push Name Setting',
            self::USER_ABOUT => 'User About',
            self::APP_STATE => 'App State',
            self::APP_STATE_SYNC_COMPLETE => 'App State Sync Complete',
            self::HISTORY_SYNC => 'History Sync',
            self::OFFLINE_SYNC_COMPLETED => 'Offline Sync Completed',
            self::OFFLINE_SYNC_PREVIEW => 'Offline Sync Preview',
            self::CALL_OFFER => 'Incoming Call',
            self::CALL_ACCEPT => 'Call Accepted',
            self::CALL_TERMINATE => 'Call Terminated',
            self::CALL_OFFER_NOTICE => 'Call Notice',
            self::CALL_RELAY_LATENCY => 'Call Latency',
            self::PRESENCE => 'User Presence',
            self::CHAT_PRESENCE => 'Chat Presence',
            self::IDENTITY_CHANGE => 'Identity Change',
            self::CAT_REFRESH_ERROR => 'CAT Refresh Error',
            self::NEWSLETTER_JOIN => 'Newsletter Joined',
            self::NEWSLETTER_LEAVE => 'Newsletter Left',
            self::NEWSLETTER_MUTE_CHANGE => 'Newsletter Mute Change',
            self::NEWSLETTER_LIVE_UPDATE => 'Newsletter Live Update',
            self::FB_MESSAGE => 'Facebook Message',
            self::ALL => 'All Events',
            self::UNKNOWN => 'Unknown',
        };
    }

    /**
     * Detect event type from webhook payload
     */
    public static function detect(array $data): self
    {
        $data = $data['type'] ?? null;

        return match ($data) {
            'Message' => self::MESSAGE,
            'UndecryptableMessage' => self::UNDECRYPTABLE_MESSAGE,
            'Receipt' => self::RECEIPT,
            'ReadReceipt' => self::READ_RECEIPT,
            'MediaRetry' => self::MEDIA_RETRY,
            'GroupInfo' => self::GROUP_INFO,
            'JoinedGroup' => self::JOINED_GROUP,
            'Picture' => self::PICTURE,
            'BlocklistChange' => self::BLOCKLIST_CHANGE,
            'Blocklist' => self::BLOCKLIST,
            'Connected' => self::CONNECTED,
            'Disconnected' => self::DISCONNECTED,
            'ConnectFailure' => self::CONNECT_FAILURE,
            'KeepAliveRestored' => self::KEEP_ALIVE_RESTORED,
            'KeepAliveTimeout' => self::KEEP_ALIVE_TIMEOUT,
            'LoggedOut' => self::LOGGED_OUT,
            'ClientOutdated' => self::CLIENT_OUTDATED,
            'TemporaryBan' => self::TEMPORARY_BAN,
            'StreamError' => self::STREAM_ERROR,
            'StreamReplaced' => self::STREAM_REPLACED,
            'PairSuccess' => self::PAIR_SUCCESS,
            'PairError' => self::PAIR_ERROR,
            'QR' => self::QR,
            'QRScannedWithoutMultidevice' => self::QR_SCANNED_WITHOUT_MULTIDEVICE,
            'PrivacySettings' => self::PRIVACY_SETTINGS,
            'PushNameSetting' => self::PUSH_NAME_SETTING,
            'UserAbout' => self::USER_ABOUT,
            'AppState' => self::APP_STATE,
            'AppStateSyncComplete' => self::APP_STATE_SYNC_COMPLETE,
            'HistorySync' => self::HISTORY_SYNC,
            'OfflineSyncCompleted' => self::OFFLINE_SYNC_COMPLETED,
            'OfflineSyncPreview' => self::OFFLINE_SYNC_PREVIEW,
            'CallOffer' => self::CALL_OFFER,
            'CallAccept' => self::CALL_ACCEPT,
            'CallTerminate' => self::CALL_TERMINATE,
            'CallOfferNotice' => self::CALL_OFFER_NOTICE,
            'CallRelayLatency' => self::CALL_RELAY_LATENCY,
            'Presence' => self::PRESENCE,
            'ChatPresence' => self::CHAT_PRESENCE,
            'IdentityChange' => self::IDENTITY_CHANGE,
            'CATRefreshError' => self::CAT_REFRESH_ERROR,
            'NewsletterJoin' => self::NEWSLETTER_JOIN,
            'NewsletterLeave' => self::NEWSLETTER_LEAVE,
            'NewsletterMuteChange' => self::NEWSLETTER_MUTE_CHANGE,
            'NewsletterLiveUpdate' => self::NEWSLETTER_LIVE_UPDATE,
            'FBMessage' => self::FB_MESSAGE,
            'All' => self::ALL,
            default => self::UNKNOWN,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MESSAGE, self::RECEIPT, self::READ_RECEIPT => 'green',
            self::UNDECRYPTABLE_MESSAGE, self::MEDIA_RETRY => 'yellow',
            self::DISCONNECTED, self::CONNECT_FAILURE, self::TEMPORARY_BAN, self::STREAM_ERROR, self::STREAM_REPLACED, self::LOGGED_OUT, self::CLIENT_OUTDATED => 'red',
            self::CONNECTED, self::KEEP_ALIVE_RESTORED, self::APP_STATE_SYNC_COMPLETE, self::OFFLINE_SYNC_COMPLETED => 'blue',
            self::QR, self::QR_SCANNED_WITHOUT_MULTIDEVICE => 'purple',
            self::PRIVACY_SETTINGS, self::PUSH_NAME_SETTING, self::USER_ABOUT => 'cyan',
            self::APP_STATE, self::HISTORY_SYNC, self::OFFLINE_SYNC_PREVIEW => 'indigo',
            self::CALL_OFFER, self::CALL_ACCEPT, self::CALL_TERMINATE, self::CALL_OFFER_NOTICE, self::CALL_RELAY_LATENCY => 'orange',
            self::PRESENCE, self::CHAT_PRESENCE => 'teal',
            self::IDENTITY_CHANGE => 'pink',
            self::CAT_REFRESH_ERROR => 'gray',
            self::NEWSLETTER_JOIN, self::NEWSLETTER_LEAVE, self::NEWSLETTER_MUTE_CHANGE, self::NEWSLETTER_LIVE_UPDATE => 'lime',
            self::FB_MESSAGE => 'blue',
            self::ALL => 'black',
            self::UNKNOWN => 'gray',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
