<?php

namespace App\Controllers\Api;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;

class OrderController extends ApiController
{
    protected OrderModel $orderModel;
    protected OrderItemModel $orderItemModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->productModel = new ProductModel();
    }

    /**
     * POST /api/orders
     * 建立訂單
     */
    public function create(): ResponseInterface
    {
        $user = $this->getCurrentUser();
        $input = $this->getJsonInput();

        // 驗證必填欄位
        $validation = service('validation');
        $validation->setRules([
            'items'                      => 'required',
            'items.*.id'                 => 'required',
            'items.*.quantity'           => 'required|integer|greater_than[0]',
            'shippingInfo.recipientName' => 'required|min_length[1]|max_length[100]',
            'shippingInfo.recipientPhone'=> 'required|min_length[10]|max_length[20]',
            'shippingInfo.city'          => 'required|max_length[50]',
            'shippingInfo.district'      => 'required|max_length[50]',
            'shippingInfo.address'       => 'required|max_length[255]',
            'shippingInfo.postalCode'    => 'required|max_length[10]',
            'paymentMethod'              => 'required|in_list[credit_card,atm,cod]',
        ], [
            'items' => [
                'required' => '商品項目為必填',
            ],
            'shippingInfo.recipientName' => [
                'required' => '收件人姓名為必填',
            ],
            'shippingInfo.recipientPhone' => [
                'required' => '收件人電話為必填',
            ],
            'shippingInfo.city' => [
                'required' => '城市為必填',
            ],
            'shippingInfo.district' => [
                'required' => '區域為必填',
            ],
            'shippingInfo.address' => [
                'required' => '地址為必填',
            ],
            'shippingInfo.postalCode' => [
                'required' => '郵遞區號為必填',
            ],
            'paymentMethod' => [
                'required' => '付款方式為必填',
                'in_list'  => '付款方式無效',
            ],
        ]);

        if (!$validation->run($input)) {
            return $this->validationError($validation->getErrors());
        }

        $items = $input['items'] ?? [];
        $shippingInfo = $input['shippingInfo'] ?? [];
        
        if (empty($items)) {
            return $this->error('請至少選擇一項商品', 400);
        }

        // 驗證商品並計算金額
        $orderItems = [];
        $subtotal = 0;

        foreach ($items as $item) {
            $product = $this->productModel->find($item['id']);
            
            if (!$product) {
                return $this->error("商品 {$item['id']} 不存在", 400);
            }

            if (!$this->productModel->hasStock($item['id'], $item['quantity'])) {
                return $this->error("商品 {$product->name} 庫存不足", 400);
            }

            $itemTotal = $product->price * $item['quantity'];
            $subtotal += $itemTotal;

            $orderItems[] = [
                'product_id'        => $product->id,
                'product_name'      => $product->name,
                'product_thumbnail' => $product->thumbnail,
                'price'             => $product->price,
                'quantity'          => $item['quantity'],
            ];
        }

        // 計算運費（範例：滿 1000 免運，否則 60 元）
        $shipping = $subtotal >= 1000 ? 0 : 60;
        $discount = 0;
        $total = $subtotal + $shipping - $discount;

        // 開始交易
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 建立訂單
            $orderId = $this->orderModel->generateId();
            $orderNumber = $this->orderModel->generateOrderNumber();

            $orderData = [
                'id'              => $orderId,
                'user_id'         => $user->id,
                'order_number'    => $orderNumber,
                'subtotal'        => $subtotal,
                'shipping'        => $shipping,
                'discount'        => $discount,
                'total'           => $total,
                'status'          => 'pending',
                'payment_method'  => $input['paymentMethod'],
                'recipient_name'  => $shippingInfo['recipientName'],
                'recipient_phone' => $shippingInfo['recipientPhone'],
                'city'            => $shippingInfo['city'],
                'district'        => $shippingInfo['district'],
                'address'         => $shippingInfo['address'],
                'postal_code'     => $shippingInfo['postalCode'],
            ];

            $this->orderModel->skipValidation()->insert($orderData);

            // 建立訂單項目
            foreach ($orderItems as &$item) {
                $item['order_id'] = $orderId;
            }
            $this->orderItemModel->insertBatch($orderItems);

            // 扣除庫存
            foreach ($items as $item) {
                $this->productModel->updateStock($item['id'], $item['quantity']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->error('訂單建立失敗，請稍後再試', 500);
            }

            // 取得完整訂單資料
            $order = $this->orderModel->find($orderId);
            $order->setOrderItems(
                array_map(
                    fn($i) => new \App\Entities\OrderItem($i),
                    $orderItems
                )
            );

            return $this->success($order->toDetailResponse(), 201);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Order creation failed: ' . $e->getMessage());
            return $this->error('訂單建立失敗，請稍後再試', 500);
        }
    }

    /**
     * GET /api/orders
     * 取得訂單列表
     */
    public function index(): ResponseInterface
    {
        $user = $this->getCurrentUser();

        $params = [
            'page'   => (int) ($this->request->getGet('page') ?? 1),
            'limit'  => (int) ($this->request->getGet('limit') ?? 10),
            'status' => $this->request->getGet('status'),
        ];

        // 限制每頁數量
        $params['limit'] = min(max($params['limit'], 1), 100);
        $params['page'] = max($params['page'], 1);

        $result = $this->orderModel->getOrdersByUser($user->id, $params);

        return $this->success([
            'orders' => array_map(fn($o) => $o->toListResponse(), $result['orders']),
            'total'  => $result['total'],
            'page'   => $result['page'],
            'limit'  => $result['limit'],
        ]);
    }

    /**
     * GET /api/orders/:id
     * 取得訂單詳情
     */
    public function show(string $id): ResponseInterface
    {
        $user = $this->getCurrentUser();
        
        $order = $this->orderModel->getOrderByUser($id, $user->id);

        if (!$order) {
            return $this->error('訂單不存在', 404);
        }

        // 載入訂單項目
        $items = $this->orderItemModel->getByOrderId($id);
        $order->setOrderItems($items);

        return $this->success($order->toDetailResponse());
    }
}
