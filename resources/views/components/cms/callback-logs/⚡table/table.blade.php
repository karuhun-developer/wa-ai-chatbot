<div>
    <div class="flex items-center justify-between mt-5 mb-4 gap-4">
        <div class="flex items-center gap-2">
            <p class="text-sm text-gray-600">Show</p>
            <flux:select size="sm" wire:model.live.debounce="paginate" placeholder="Per Page">
                <option value="10">10 Per Page</option>
                <option value="25">25 Per Page</option>
                <option value="50">50 Per Page</option>
                <option value="100">100 Per Page</option>
            </flux:select>
        </div>

        <div class="flex items-center gap-2">
            <flux:input.group>
                <flux:input
                    size="sm"
                    icon="magnifying-glass"
                    type="text"
                    placeholder="Search ...."
                    wire:model.live.debounce="search"
                    class="max-w-xs"
                />
            </flux:input.group>
        </div>
    </div>

    <flux:table :paginate="$data" class="min-w-full">
        <flux:table.columns>
            <flux:table.column>
                Actions
            </flux:table.column>
            <flux:table.column>
                Device
            </flux:table.column>
            <x-loop-th :$searchBy :$paginationOrder :$paginationOrderBy />
        </flux:table.columns>
        <flux:table.rows>
            @forelse($data as $d)
                <flux:table.row>
                    <flux:table.cell>
                        <flux:button
                            size="sm"
                            variant="primary"
                            icon="eye"
                            href="{{ route('cms.callback-logs.show') }}?log={{ $d->id }}"
                            wire:navigate
                        >
                            View
                        </flux:button>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $d->device?->name ?? '-' }}
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge color="{{ $d->event_type->color() }}" size="sm">
                            {{ $d->event_type->label() }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $d->ip_address }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $d->created_at->format('Y-m-d H:i:s') }}
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="999" align="center" variant="strong">
                        No data found.
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
