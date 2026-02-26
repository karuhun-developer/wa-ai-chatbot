
<div>
    <form
        wire:submit="save"
        class="space-y-6"
    >
        <div class="flex items-center justify-between border-b bg-card" x-data="{
            tab: 'ai_configuration',
        }">
            <div
                class="flex w-full gap-6 overflow-x-auto px-6 md:w-auto"
            >
                <flux:button
                    type="button"
                    variant="ghost"
                    class="relative rounded-none px-0 py-4 text-sm font-medium whitespace-nowrap text-foreground hover:bg-transparent"
                    :class="{
                        '!bg-transparent': tab === 'ai_configuration',
                    }"
                    type="button"
                    @click="tab = 'ai_configuration'"
                >
                    AI Configuration
                    <span
                        class="absolute right-0 bottom-0 left-0 h-0.5 bg-primary"
                        x-show="tab === 'ai_configuration'"
                    ></span>
                </flux:button>
            </div>

            <div class="hidden px-6 py-2 md:block">
                <flux:button
                    variant="primary"
                    type="submit"
                >
                    Save Settings
                </flux:button>
            </div>
        </div>

        <div class="space-y-4 rounded-lg border bg-card p-6">
            <div class="grid gap-2">
                <Label>System Prompt</Label>
                <flux:textarea
                    wire:model="system_prompt"
                    placeholder="Enter the main system prompt for the AI..."
                    rows="35"
                />
                <flux:error name="system_prompt" />
            </div>
        </div>
    </form>
</div>
