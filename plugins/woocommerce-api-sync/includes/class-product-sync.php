<?php

class Product_Sync {
    private $api;
    private $logger;

    public function __construct() {
        $this->api = new API_Client();
        $this->logger = new Sync_Logger();
    }

    public function sync_from_api() {
        try {
            $products = $this->api->get_products();
            foreach ($products as $p) {
                $existing = wc_get_product_id_by_sku($p['sku']);
                if ($existing) {
                    $product = wc_get_product($existing);
                } else {
                    $product = new WC_Product_Simple();
                    $product->set_sku($p['sku']);
                }

                $product->set_name($p['name']);
                $product->set_price($p['price']);
                $product->set_regular_price($p['price']);
                $product->set_stock_quantity($p['stock']);
                $product->save();
            }

            $this->logger->log('Đã đồng bộ từ API thành công');
        } catch (Exception $e) {
            $this->logger->log('Lỗi khi đồng bộ từ API: ' . $e->getMessage());
        }
    }

    public function sync_to_api() {
        try {
            $args = [
                'status' => ['publish'],
                'limit' => -1,
                'return' => 'ids',
            ];
            $product_ids = wc_get_products($args);

            foreach ($product_ids as $id) {
                $product = wc_get_product($id);
                $data = [
                    'sku' => $product->get_sku(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'stock' => $product->get_stock_quantity(),
                ];
                $this->api->update_product($data);
            }

            $this->logger->log('Đã đồng bộ lên API thành công');
        } catch (Exception $e) {
            $this->logger->log('Lỗi khi đồng bộ lên API: ' . $e->getMessage());
        }
    }
}