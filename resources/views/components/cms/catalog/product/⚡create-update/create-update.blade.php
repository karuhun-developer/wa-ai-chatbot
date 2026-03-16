
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
                    {{ $isUpdate ? 'Update' : 'Create' }} Product
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the product below.' : 'Fill in the details to create a new product.' }}
                </flux:text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label badge="Required">SKU</flux:label>
                    <flux:text>Stock Keeping Unit.</flux:text>
                    <flux:input wire:model="sku" type="text" />
                    <flux:error name="sku" />
                </flux:field>

                <flux:field>
                    <flux:label badge="Required">Name</flux:label>
                    <flux:text>The name of the product.</flux:text>
                    <flux:input wire:model="name" type="text" />
                    <flux:error name="name" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Description</flux:label>
                <flux:text>A detailed description of the product.</flux:text>
                <flux:textarea wire:model="description" />
                <flux:error name="description" />
            </flux:field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Price</flux:label>
                    <flux:text>Set price for product.</flux:text>
                    <flux:input wire:model="price" x-mask:dynamic="$money($input, ',')" />
                    <flux:error name="price" />
                </flux:field>

                <flux:field>
                    <flux:label>Stock</flux:label>
                    <flux:text>Stock availability.</flux:text>
                    <flux:input wire:model="stock" x-mask:dynamic="$money($input, ',')" />
                    <flux:error name="stock" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Checkout URL</flux:label>
                <flux:text>External checkout URL (if any).</flux:text>
                <flux:input wire:model="checkout_url" type="url" />
                <flux:error name="checkout_url" />
            </flux:field>

            <flux:checkbox.group wire:model="product_categories" label="Product Categories">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                    @foreach($this->categories as $category)
                        <flux:checkbox value="{{ $category->id }}" label="{{ $category->name }}" />
                    @endforeach
                </div>
                <flux:error name="product_categories" />
            </flux:checkbox.group>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
