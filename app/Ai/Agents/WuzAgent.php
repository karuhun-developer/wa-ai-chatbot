<?php

namespace App\Ai\Agents;

use App\Ai\Tools\GetProduct;
use App\Models\Setting\Setting;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;

#[Provider('openrouter')]
#[Temperature(0.7)]
#[Model('deepseek/deepseek-chat-v3.1')]
class WuzAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function instructions(): string
    {
        $setting = Setting::first();

        $basePrompt = $setting?->value['system_prompt'] ?? 'You are a helpful assistant.';

        $productToolInfo = <<<'PROMPT'

## TOOLS: GET PRODUCT
Kamu punya akses ke tool `get_product` untuk mengambil data produk dari toko.

Gunakan tool ini kalau user bertanya tentang:
- Produk apa saja yang tersedia
- Mencari produk berdasarkan nama
- Mencari produk berdasarkan kategori
- Menanyakan harga atau range harga produk
- Membandingkan produk

Contoh percakapan KAPAN pakai tool ini:

User: "ada produk apa aja nih?"
→ Panggil get_product tanpa filter, lalu sampaikan hasilnya dalam gaya chat santai.

User: "cari dong baju yang di bawah 200rb"
→ Panggil get_product dengan max_price = 200000.

User: "ada sepatu kategori olahraga gak?"
→ Panggil get_product dengan category = "olahraga".

User: "ada produk namanya galon gak?"
→ Panggil get_product dengan name = "galon".

User: "mau liat produk elektronik yang harganya 100rb-500rb"
→ Panggil get_product dengan category = "elektronik", min_price = 100000, max_price = 500000.

Setelah dapat hasil, sampaikan dalam gaya WhatsApp yang santai sesuai aturan di atas. Jangan tempel raw data mentah.
PROMPT;

        return $basePrompt.$productToolInfo;
    }

    /** @return array<int, \Laravel\Ai\Contracts\Tool> */
    public function tools(): iterable
    {
        return [new GetProduct];
    }
}
