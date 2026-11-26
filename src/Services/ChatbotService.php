<?php


declare(strict_types=1);


namespace App\Services;


use App\Core\Env;
use App\Core\StreamAborted;
use App\Services\Chatbot\ChatbotTools;
use App\Services\Chatbot\GroqClient;
use App\Services\Chatbot\ProductEmbeddingIndexer;
use Throwable;


final class ChatbotService
{
    private const MAX_STEPS = 4;
    private const FALLBACK = 'Xin lỗi, mình đang gặp sự cố. Bạn thử lại sau ít phút nhé.';
    private const ALLOWED_INTERNAL_PREFIXES = [
        '/contact',
        '/orders',
        '/cart',
        '/login',
        '/register',
        '/products',
    ];


    private const SYSTEM_PROMPT = <<<'PROMPT'
Bạn là trợ lý chuyên gia làm bánh thân thiện của BakeHouse, CHỈ hỗ trợ các chủ đề liên quan đến cửa hàng BakeHouse: nguyên liệu/dụng cụ làm bánh, sản phẩm, đơn hàng, chính sách giao hàng/thanh toán, thông tin liên hệ cửa hàng.
Quy tắc bắt buộc:
0. Nếu khách hỏi điều gì đó hoàn toàn không liên quan đến làm bánh / BakeHouse (vd: tình cảm, tâm sự cá nhân, chính trị, kiến thức chung không liên quan...), từ chối lịch sự và mời họ quay lại hỏi về bánh/nguyên liệu/dụng cụ/đơn hàng của BakeHouse. KHÔNG đưa lời khuyên về chủ đề ngoài lề đó.
0b. Nếu khách hỏi thông tin cửa hàng (địa chỉ, hotline, email, giờ mở cửa, phí ship, thanh toán): BẮT BUỘC gọi `get_shop_policies` rồi trả lời thẳng bằng dữ liệu thật nhận được (địa chỉ, số điện thoại, email, giờ mở cửa...), KHÔNG chỉ dẫn vòng vo sang `/contact` nếu tool đã trả về đủ thông tin.
0c. Nếu khách hỏi ngày/giờ/thứ trùng với các mốc (hôm nay, ngày mai, ngày kia, 3 ngày nữa) đã liệt kê sẵn ở cuối system prompt: lấy đúng mốc đó, KHÔNG tự cộng trừ lại.
0d. Nếu khách hỏi điều cần suy luận/tính toán mà BẠN KHÔNG có dữ kiện chắc chắn (vd: ngày xa hơn các mốc đã cho, phép tính, đố vui, kiến thức chung ngoài phạm vi bánh/BakeHouse): thẳng thắn nói bạn không chắc chắn về việc này và mời khách hỏi về bánh/nguyên liệu/dụng cụ/đơn hàng/chính sách của BakeHouse. KHÔNG đoán hoặc tự suy luận ra câu trả lời có thể sai.
1. Gợi ý / so sánh nguyên liệu / dụng cụ / món bánh:
  - LUÔN gọi `search_bakery_products` trước để lấy sản phẩm thật trong kho. Đưa đề xuất ngay ở lượt đầu, trả lời trọng tâm, không hỏi ngược vòng vo.
  - CHỈ dùng tên, đơn vị và giá có trong kết quả tool; KHÔNG tự bịa sản phẩm hoặc giá. Nếu không có hàng phù hợp, báo là hiện chưa có hàng và hướng dẫn `[Form Liên hệ BakeHouse](/contact)`.
  - Tôn trọng ngân sách tối đa (nếu có) và BẮT BUỘC tính/nhân số lượng `quantity >= 1` theo quy mô khách nêu:
    + Nếu khách nêu số người ăn (vd: 10 người, 20 người): ước lượng định lượng tiêu hao (thường ~100–150g bánh/người hoặc quy đổi theo đơn vị sản phẩm) và đặt `quantity` tương ứng (vd: 5, 10, 20...) thay vì chỉ để 1.
    + Nếu khách nêu khối lượng bánh thành phẩm (vd: 2 kg, 5 kg bánh) hoặc số lượng bánh/phần (vd: 10 chiếc, 20 phần): quy đổi theo đơn vị từng sản phẩm trong kho rồi truyền `quantity` cụ thể vào `finalize_product_bundle` để thẻ sản phẩm hiện sẵn đúng số lượng gợi ý cho khách.
  - Khi gợi ý hoặc so sánh các sản phẩm có trong kho để khách chọn mua, BẮT BUỘC gọi `finalize_product_bundle` với `items` (`id`, `quantity` >= 1) hoặc `product_ids` để gắn thẻ giỏ hàng cho các món đó.
  - KHÔNG tự chèn câu hoặc link dẫn tới "Thanh toán" / `/checkout` khi gợi ý nguyên liệu hoặc sản phẩm.
2. Cách trình bày:
  - Trả lời tiếng Việt ngắn gọn, rõ ràng; không dùng tiêu đề lớn hay khối code.
  - Nếu khách gõ sai/thiếu dấu tên món (vd: "crosna", "crosan", "socola"...), mở đầu bằng câu hỏi xác nhận ngắn gọn (vd: "Ý bạn là bánh croissant đúng không?") rồi đưa gợi ý ngay.
  - Nếu khách hỏi so sánh / cái nào ngon hơn / chọn loại nào, mở đầu ngắn gọn, kẻ bảng so sánh dọc (`| Tiêu chí | ... |` với các hàng `Giá`, `Đặc điểm`, `Độ phù hợp`) và chốt 1–2 câu.
  - KHÔNG tự bịa đường dẫn nội bộ ngoài `/contact`, `/login`, `/orders`, `/cart`, `/products`.
  - KHÔNG in khối JSON thô, `product_ids`, hay dòng `IDS:` / `BUDGET:` ra phần chữ trả lời.
3. Chính sách giao hàng, thanh toán, FAQ: gọi `get_shop_policies`.
4. Đơn hàng: gọi `check_my_orders` (nếu chưa đăng nhập thì nhắc đăng nhập và kèm link `[Đăng nhập](/login)`).
5. CSKH / khiếu nại: hướng dẫn `[Form Liên hệ BakeHouse](/contact)`.
PROMPT;


    private GroqClient $client;
    private ChatbotTools $tools;


    public function __construct(?GroqClient $client = null, ?ChatbotTools $tools = null)
    {
        $this->client = $client ?? new GroqClient();
        $this->tools = $tools ?? new ChatbotTools(new ProductEmbeddingIndexer($this->client));
    }


    /**
     * @param array<int, array{role: string, content?: string, text?: string}> $history
     * @param (callable(string, array<string, mixed>): void)|null $emit
     * @return array{message: string, products: array<int, array<string, mixed>>, total: int}
     */
    public function chat(array $history, ?callable $emit = null): array
    {
        if (trim((string) Env::get('GROQ_API_KEY', '')) === '') {
            return [
                'message' => 'Chưa cấu hình GROQ_API_KEY trong file .env.',
                'products' => [],
                'total' => 0,
            ];
        }


        $normalizedHistory = [];
        foreach (array_slice($history, -6) as $turn) {
            if (!is_array($turn)) {
                continue;
            }
            $role = ($turn['role'] ?? 'user') === 'assistant' || ($turn['role'] ?? '') === 'model'
                ? 'assistant'
                : 'user';
            $text = trim((string) ($turn['content'] ?? $turn['text'] ?? ''));
            if ($text !== '') {
                $normalizedHistory[] = ['role' => $role, 'content' => $text];
            }
        }


        if ($normalizedHistory === []) {
            return [
                'message' => 'Bạn hãy nhập câu hỏi hoặc món bánh muốn làm để mình hỗ trợ nhé!',
                'products' => [],
                'total' => 0,
            ];
        }


        $lastUserText = '';
        for ($i = count($normalizedHistory) - 1; $i >= 0; $i--) {
            if ($normalizedHistory[$i]['role'] === 'user') {
                $lastUserText = $normalizedHistory[$i]['content'];
                break;
            }
        }
        $userBudget = $this->budgetFromUser($lastUserText);


        $messages = array_merge(
            [['role' => 'system', 'content' => self::SYSTEM_PROMPT . "\n\n" . $this->currentDateContext()]],
            $normalizedHistory
        );
        $groqTools = $this->buildGroqTools($this->tools->declarations());


        $finalProducts = [];
        $finalTotal = 0;
        $discoveredProductIds = [];
        $budgetRetried = false;


        try {
            for ($step = 0; $step < self::MAX_STEPS; $step++) {
                $stepTools = ($step === self::MAX_STEPS - 1) ? [] : $groqTools;


                try {
                    $response = $this->client->chatCompletions($messages, $stepTools, 0.4, 1500);
                } catch (Throwable $stepErr) {
                    $msg = $stepErr->getMessage();
                    if ($stepTools !== [] && (stripos($msg, 'Tool call validation failed') !== false || stripos($msg, '<|channel|>') !== false)) {
                        $groqTools = [];
                        $fallbackRes = $this->client->chatCompletions($messages, [], 0.4, 1500);
                        $plainReply = trim((string) ($fallbackRes['choices'][0]['message']['content'] ?? ''));
                        $replyText = $this->sanitizeReplyText($plainReply, false);
                        if ($replyText === '') {
                            $replyText = self::FALLBACK;
                        }
                        $this->emitText($replyText, $emit);


                        return [
                            'message' => $replyText,
                            'products' => [],
                            'total' => 0,
                        ];
                    }
                    throw $stepErr;
                }


                $choiceMessage = $response['choices'][0]['message'] ?? null;
                if (!is_array($choiceMessage)) {
                    break;
                }


                $toolCalls = is_array($choiceMessage['tool_calls'] ?? null)
                    ? $choiceMessage['tool_calls']
                    : [];


                if ($toolCalls === []) {
                    $rawReply = trim((string) ($choiceMessage['content'] ?? ''));


                    if ($finalProducts === [] && $rawReply !== '') {
                        $extracted = $this->rescueBundleFromText($rawReply);
                        if ($extracted['products'] !== []) {
                            $finalProducts = $extracted['products'];
                            $finalTotal = $extracted['total'];
                        }
                    }


                    if ($finalProducts === [] && $discoveredProductIds !== [] && $this->shouldAttachBundle($lastUserText, $rawReply)) {
                        $autoBundle = $this->tools->execute('finalize_product_bundle', [
                            'product_ids' => array_slice(array_values(array_unique($discoveredProductIds)), 0, 4),
                        ]);
                        if (!empty($autoBundle['products'])) {
                            $finalProducts = $autoBundle['products'];
                            $finalTotal = (int) ($autoBundle['total'] ?? 0);
                        }
                    }


                    if (!$budgetRetried && $userBudget > 0 && $finalTotal > $userBudget) {
                        $budgetRetried = true;
                        if ($emit !== null) {
                            $emit('status', ['text' => 'Đang điều chỉnh theo ngân sách của bạn…']);
                        }
                        $messages[] = ['role' => 'assistant', 'content' => $rawReply];
                        $messages[] = [
                            'role' => 'user',
                            'content' => sprintf(
                                '[Hệ thống] Tổng giá bộ sản phẩm hiện tại là %s ₫, vượt ngân sách %s ₫. Hãy gọi lại finalize_product_bundle với món/số lượng phù hợp để tổng không vượt ngân sách.',
                                number_format($finalTotal, 0, ',', '.'),
                                number_format($userBudget, 0, ',', '.')
                            ),
                        ];
                        continue;
                    }


                    $replyText = $this->sanitizeReplyText($rawReply, $finalProducts !== []);
                    if ($replyText === '') {
                        $replyText = $finalProducts !== []
                            ? 'Mình đã gợi ý các sản phẩm phù hợp bên dưới để bạn dễ chọn và thêm vào giỏ nhé:'
                            : 'Mình đã giải đáp thông tin cho bạn, bạn cần hỗ trợ thêm món hay nguyên liệu nào không?';
                    }


                    if ($userBudget > 0 && $finalTotal > $userBudget) {
                        $replyText .= sprintf(
                            "\n(Lưu ý: Tổng ước tính %s ₫ đang vượt ngân sách %s ₫, bạn có thể điều chỉnh số lượng hoặc bớt món ở danh sách bên dưới.)",
                            number_format($finalTotal, 0, ',', '.'),
                            number_format($userBudget, 0, ',', '.')
                        );
                    }


                    $this->emitText($replyText, $emit);


                    return [
                        'message' => $replyText,
                        'products' => $finalProducts,
                        'total' => $finalTotal,
                    ];
                }


                $messages[] = [
                    'role' => 'assistant',
                    'content' => $choiceMessage['content'] ?? null,
                    'tool_calls' => $toolCalls,
                ];


                foreach ($toolCalls as $toolCall) {
                    $callId = (string) ($toolCall['id'] ?? '');
                    $rawFnName = (string) ($toolCall['function']['name'] ?? '');
                    $fnName = trim((string) preg_replace('/<\|.*$/s', '', $rawFnName));
                    if ($emit !== null) {
                        $emit('status', ['text' => $this->statusForTool($fnName)]);
                    }


                    $rawArgs = $toolCall['function']['arguments'] ?? '{}';
                    $decodedArgs = is_string($rawArgs) ? json_decode($rawArgs, true) : (array) $rawArgs;
                    $fnArgs = is_array($decodedArgs) ? $decodedArgs : [];


                    $toolOutput = $this->tools->execute($fnName, $fnArgs);


                    if ($fnName === 'search_bakery_products' && isset($toolOutput['products']) && is_array($toolOutput['products'])) {
                        foreach ($toolOutput['products'] as $p) {
                            if (is_array($p) && isset($p['id']) && (int) $p['id'] > 0) {
                                $discoveredProductIds[] = (int) $p['id'];
                            }
                        }
                    }


                    if ($fnName === 'finalize_product_bundle') {
                        $finalProducts = is_array($toolOutput['products'] ?? null) ? $toolOutput['products'] : [];
                        $finalTotal = (int) ($toolOutput['total'] ?? 0);
                    }


                    $compactOutput = $this->compactToolOutputForModel($fnName, $toolOutput);


                    $messages[] = [
                        'role' => 'tool',
                        'tool_call_id' => $callId,
                        'content' => json_encode($compactOutput, JSON_UNESCAPED_UNICODE) ?: '{}',
                    ];
                }
            }


            if ($finalProducts === [] && $discoveredProductIds !== []) {
                $autoBundle = $this->tools->execute('finalize_product_bundle', [
                    'product_ids' => array_slice(array_values(array_unique($discoveredProductIds)), 0, 4),
                ]);
                if (!empty($autoBundle['products'])) {
                    $finalProducts = $autoBundle['products'];
                    $finalTotal = (int) ($autoBundle['total'] ?? 0);
                }
            }


            $fallbackMsg = $finalProducts !== []
                ? 'Mình đã tổng hợp các sản phẩm có sẵn trong kho bên dưới để bạn tham khảo và thêm vào giỏ nhé:'
                : 'Bạn cần mình gợi ý nguyên liệu cho món bánh nào thì nhắn thêm nhé!';
            $this->emitText($fallbackMsg, $emit);


            return [
                'message' => $fallbackMsg,
                'products' => $finalProducts,
                'total' => $finalTotal,
            ];
        } catch (StreamAborted $e) {
            throw $e;
        } catch (Throwable $e) {
            @file_put_contents(dirname(__DIR__, 2) . '/storage/logs/app.log', '[' . date('Y-m-d H:i:s') . '] [BakeHouse Groq Chat Error] ' . $e->getMessage() . "\n", FILE_APPEND);
            $this->emitText(self::FALLBACK, $emit);


            return [
                'message' => self::FALLBACK,
                'products' => [],
                'total' => 0,
            ];
        }
    }


    private function currentDateContext(): string
    {
        $weekdays = [
            'Sunday' => 'Chủ Nhật',
            'Monday' => 'Thứ Hai',
            'Tuesday' => 'Thứ Ba',
            'Wednesday' => 'Thứ Tư',
            'Thursday' => 'Thứ Năm',
            'Friday' => 'Thứ Sáu',
            'Saturday' => 'Thứ Bảy',
        ];


        $now = new \DateTimeImmutable('now');
        $labelFor = static fn (\DateTimeImmutable $d): string => ($weekdays[$d->format('l')] ?? $d->format('l')) . ', ngày ' . $d->format('d/m/Y');


        return sprintf(
            "NGÀY HIỆN TẠI (dùng đúng các mốc sau, KHÔNG tự tính lại):\n- Hôm nay: %s\n- Ngày mai: %s\n- Ngày kia: %s\n- 3 ngày nữa: %s",
            $labelFor($now),
            $labelFor($now->modify('+1 day')),
            $labelFor($now->modify('+2 days')),
            $labelFor($now->modify('+3 days'))
        );
    }


    private function shouldAttachBundle(string $userText, string $replyText): bool
    {
        $combined = $userText . ' ' . $replyText;
        return (bool) preg_match('/(?:hay|so sánh|ngon hơn|tốt hơn|chọn loại|mua|làm bánh|nguyên liệu|dụng cụ|bao nhiêu|giá)/iu', $combined);
    }


    private function emitText(string $text, ?callable $emit): void
    {
        if ($emit !== null && $text !== '') {
            $emit('delta', ['text' => $text]);
        }
    }


    private function statusForTool(string $fnName): string
    {
        return match ($fnName) {
            'search_bakery_products' => 'Đang tìm nguyên liệu & dụng cụ trong kho…',
            'finalize_product_bundle' => 'Đang tổng hợp bộ sản phẩm gợi ý…',
            'get_shop_policies' => 'Đang xem thông tin cửa hàng…',
            'check_my_orders' => 'Đang tra cứu đơn hàng của bạn…',
            default => 'Đang xử lý…',
        };
    }


    private function budgetFromUser(string $text): int
    {
        if (!preg_match('/(?:dưới|ngân sách|tối đa|không quá|budget)\D{0,12}?(\d+(?:[.,]\d+)*)[ \t]*(k|nghìn|ngàn|tr|triệu|m)?\b/iu', $text, $m)) {
            return 0;
        }
        $unit = mb_strtolower($m[2] ?? '', 'UTF-8');
        $number = $m[1];


        if (in_array($unit, ['tr', 'triệu', 'm'], true)) {
            return (int) round((float) str_replace(',', '.', $number) * 1_000_000);
        }
        $value = (int) preg_replace('/\D+/', '', $number);
        if (in_array($unit, ['k', 'nghìn', 'ngàn'], true) || ($unit === '' && $value > 0 && $value < 1000)) {
            return $value * 1000;
        }


        return $value;
    }


    /**
     * @param array<string, mixed> $output
     * @return array<string, mixed>
     */
    private function compactToolOutputForModel(string $fnName, array $output): array
    {
        if ($fnName === 'finalize_product_bundle') {
            $items = [];
            foreach (($output['products'] ?? []) as $p) {
                if (!is_array($p)) {
                    continue;
                }
                $items[] = [
                    'id' => $p['id'] ?? null,
                    'name' => $p['name'] ?? '',
                    'price' => $p['price'] ?? null,
                    'quantity' => $p['quantity'] ?? 1,
                ];
            }


            return [
                'count' => count($items),
                'total' => $output['total'] ?? 0,
                'products' => $items,
            ];
        }


        if (isset($output['products']) && is_array($output['products'])) {
            $compactProducts = [];
            foreach (array_slice($output['products'], 0, 8) as $p) {
                if (!is_array($p)) {
                    continue;
                }
                $row = [
                    'id' => $p['id'] ?? null,
                    'name' => $p['name'] ?? '',
                    'price' => $p['price'] ?? null,
                ];
                if (isset($p['unit'])) {
                    $row['unit'] = $p['unit'];
                }
                if (isset($p['stock_quantity'])) {
                    $row['stock_quantity'] = $p['stock_quantity'];
                }
                $compactProducts[] = $row;
            }


            $copy = $output;
            $copy['products'] = $compactProducts;


            return $copy;
        }


        return $output;
    }


    /**
     * @return array{products: array<int, array<string, mixed>>, total: int}
     */
    private function rescueBundleFromText(string $text): array
    {
        if (!preg_match_all('/\{[^{}]*(?:product_ids|items)[^{}]*\}/s', $text, $matches)) {
            return ['products' => [], 'total' => 0];
        }


        foreach ($matches[0] as $jsonSnippet) {
            $decoded = json_decode($jsonSnippet, true);
            if (!is_array($decoded)) {
                continue;
            }
            if (isset($decoded['product_ids']) || isset($decoded['items'])) {
                $bundle = $this->tools->execute('finalize_product_bundle', $decoded);
                if (!empty($bundle['products'])) {
                    return [
                        'products' => $bundle['products'],
                        'total' => (int) ($bundle['total'] ?? 0),
                    ];
                }
            }
        }


        return ['products' => [], 'total' => 0];
    }


    private function isAllowedUrl(string $href): bool
    {
        $clean = trim($href);
        if ($clean === '' || preg_match('/^(?:javascript|data|vbscript):/i', $clean)) {
            return false;
        }
        if (preg_match('/^https?:\/\//i', $clean)) {
            return true;
        }
        if (!str_starts_with($clean, '/')) {
            return false;
        }
        foreach (self::ALLOWED_INTERNAL_PREFIXES as $prefix) {
            if ($clean === $prefix || str_starts_with($clean, $prefix . '/') || str_starts_with($clean, $prefix . '?')) {
                return true;
            }
        }


        return false;
    }


    private function sanitizeReplyText(string $text, bool $hasProducts): string
    {
        if ($text === '') {
            return '';
        }


        $cleaned = preg_replace('/```(?:json)?\s*[\s\S]*?```/i', '', $text) ?? $text;
        $cleaned = preg_replace('/\{\s*"(?:product_ids|items)"\s*:[\s\S]*?\}/', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/\b(?:IDS?|BUDGET)[ \t]*[:=][^\n]*/iu', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/[^.!?\n]*thẻ sản phẩm đã được thêm vào giỏ[^.!?\n]*[.!?]?/iu', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/\(?[^()\n]*thẻ sản phẩm sẽ hiển thị[^()\n]*\)?[.!?]?/iu', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/\(?[^()\n]*bộ chọn số lượng và nút[^()\n]*\)?[.!?]?/iu', '', $cleaned) ?? $cleaned;


        // Gỡ link hoặc câu dẫn sang trang Thanh toán / checkout khi gợi ý sản phẩm
        $cleaned = preg_replace('/\[([^\]]+)\]\(\/checkout[^)]*\)/iu', '$1', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/[^.!?\n]*(?:chuyển tới|đi tới|đến)\s+thanh toán[^.!?\n]*[.!?]?/iu', '', $cleaned) ?? $cleaned;


        $cleaned = preg_replace_callback('/\[([^\]\n]+)\]\(([^)\s]+)\)/', function (array $m): string {
            return $this->isAllowedUrl($m[2]) ? $m[0] : $m[1];
        }, $cleaned) ?? $cleaned;


        if (!$hasProducts) {
            $cleaned = preg_replace('/[^.!?\n]*(?:thẻ sản phẩm|thêm vào giỏ)[^.!?\n]*[.!?]?/iu', '', $cleaned) ?? $cleaned;
        }


        $cleaned = preg_replace('/\n{3,}/', "\n\n", $cleaned) ?? $cleaned;


        return trim($cleaned);
    }


    /**
     * @param array<int, mixed> $declarations
     * @return array<int, array<string, mixed>>
     */
    private function buildGroqTools(array $declarations): array
    {
        $groqTools = [];
        foreach ($declarations as $fn) {
            if (!is_array($fn) || !isset($fn['name'])) {
                continue;
            }
            $parameters = $fn['parameters'] ?? ['type' => 'object', 'properties' => new \stdClass()];
            $groqTools[] = [
                'type' => 'function',
                'function' => [
                    'name' => (string) $fn['name'],
                    'description' => (string) ($fn['description'] ?? ''),
                    'parameters' => $this->normalizeJsonSchema($parameters),
                ],
            ];
        }


        return $groqTools;
    }


    private function normalizeJsonSchema(mixed $schema): mixed
    {
        if (!is_array($schema)) {
            return $schema;
        }


        if (isset($schema['type']) && is_string($schema['type'])) {
            $schema['type'] = strtolower($schema['type']);
        }


        if (isset($schema['properties']) && is_array($schema['properties'])) {
            foreach ($schema['properties'] as $propKey => $propVal) {
                $schema['properties'][$propKey] = $this->normalizeJsonSchema($propVal);
            }
        }


        if (isset($schema['items'])) {
            $schema['items'] = $this->normalizeJsonSchema($schema['items']);
        }


        return $schema;
    }
}