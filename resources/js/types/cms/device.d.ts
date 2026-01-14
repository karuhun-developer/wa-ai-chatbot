import { UserDataItem } from './management';

export interface DeviceDataItem {
    id: number;
    user_id: number;
    device_id: string;
    name: string;
    token: string;
    connected: boolean;
    jid?: string;
    created_at: string;
    updated_at: string;
    user?: UserDataItem;
    callbackLogs: CallbackLogDataItem[];
}

export interface DeviceWebhookDataItem {
    id: number;
    device_id: number;
    event: string;
    url: string | null;
    status: boolean;
    created_at: string;
    updated_at: string;
}

export interface CallbackLogDataItem {
    id: number;
    device_id: number;
    device_name?: string;
    event_type: string;
    payload: any;
    ip_address: string;
    user_agent: string;
    created_at: string;
    updated_at: string;
    device?: DeviceDataItem;
}
