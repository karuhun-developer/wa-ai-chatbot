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
}
