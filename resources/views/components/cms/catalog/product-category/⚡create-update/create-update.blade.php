
<div>
    <!-- Create / Update Modal -->
    <flux:modal
        name="defaultModal"
        class="w-full max-w-7xl"
        flyout
    >
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">
                    {{ $isUpdate ? 'Update' : 'Create' }} Product Category Item
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the product category item below.' : 'Fill in the details to create a new product category item.' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Default Name</flux:label>
                <flux:text>Default name for the product category.</flux:text>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:switch wire:model="status" label="Active/Inactive" />
                <flux:text>Toggle the status of the product category. Inactive product categorys will not be available for selection.</flux:text>
                <flux:error name="status" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
