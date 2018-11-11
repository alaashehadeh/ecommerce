<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/5/2018
 * Time: 9:15 AM
 */

namespace App\Http\Services;
use App\Http\Helpers\productHelper;
use App\Http\Repositories\orderRepository;
use App\Http\Repositories\productsRepository;
use App\Http\Helpers\orderHelper;


class cartServices
{
    private $orderRepository;
    private $productRepository;
    public function __construct(orderRepository $orderRepository,productsRepository $productsRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productsRepository;
    }

    public function customerCart($customer_id) {
        $output = array();
        $data = $this->orderRepository->customerCart($customer_id);
        if($data) {
            //get the products with discount
            $products = $this->productRepository->multiProductsWithDiscount(json_decode($data['items']));
            $products = productHelper::multiProducts($products);
            $output = orderHelper::selectedCart($data,$products['products'],$products['total']);
        }

        return $output;
    }

    /**
     * @return orderRepository
     */
    public function customerOrderByProductId($customer_id,$product_id)
    {
        $data = $this->orderRepository->customerCartByProductId($customer_id,$product_id);
        return orderHelper::selectedCart($data);
    }
}