export interface Setting {
    id: number;
    value: {
        system_prompt?: string;
    };
    created_at: string;
    updated_at: string;
}
