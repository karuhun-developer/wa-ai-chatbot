
<div>
    <form
        wire:submit="submit"
        class="mt-3 space-y-6"
    >
        <!-- Phone Number -->
        <flux:field>
            <flux:label badge="Required">Phone Number</flux:label>
            <flux:text>e.g., 081234567890 or +6281234567890</flux:text>
            <flux:input wire:model="phone" type="text" />
            <flux:error name="phone" />
        </flux:field>

        <flux:field>
            <flux:label>Message Type</flux:label>
            <flux:text>Select the type of message you want to send.</flux:text>
            <flux:select wire:model.live="type" placeholder="Select message type">
                <flux:select.option value="text">Text Message</flux:select.option>
                <flux:select.option value="image">Image Message</flux:select.option>
                <flux:select.option value="video">Video Message</flux:select.option>
            </flux:select>
            <flux:error name="type" />
        </flux:field>

        @if ($type === 'text')
            <flux:field>
                <flux:label badge="Required">Message</flux:label>
                <flux:text>Enter the text message you want to send.</flux:text>
                <flux:textarea wire:model="message" placeholder="Enter your message..." rows="5" />
                <flux:error name="message" />
            </flux:field>
        @elseif ($type === 'image')
            <flux:field>
                <flux:label badge="Required">Image File</flux:label>
                <flux:text>Upload an image file (max 2MB).</flux:text>
                <x-file-preview type="image" :file="$image" />
                <x-file-upload model="image" accept="image/*" />
                <flux:error name="image" />
            </flux:field>
            <flux:field>
                <flux:label>Caption (Optional)</flux:label>
                <flux:text>Add an optional caption to accompany your image.</flux:text>
                <flux:input wire:model="caption" type="text" placeholder="Add a caption for your image..." />
                <flux:error name="caption" />
            </flux:field>
        @elseif ($type === 'video')
            <flux:field>
                <flux:label badge="Required">Video File</flux:label>
                <x-file-preview type="video" :file="$video" />
                <x-file-upload model="video" accept="video/*" />
                <flux:error name="video" />
            </flux:field>
            <flux:field>
                <flux:label>Caption (Optional)</flux:label>
                <flux:text>Add an optional caption to accompany your video.</flux:text>
                <flux:input wire:model="caption" type="text" placeholder="Add a caption for your video..." />
                <flux:error name="caption" />
            </flux:field>
        @endif
        <!-- Send Button -->
        <div class="flex justify-end pt-2">
            <flux:spacer />

            <flux:button type="submit" variant="primary">Save changes</flux:button>
        </div>
    </form>
</div>
