<?php

namespace App\Http\Controllers;

use App\Http\Helpers\orderHelper;
use App\Http\Repositories\orderRepository;
use App\Http\Services\cartServices;
use App\Http\Services\productsServices;

class orderController extends Controller
{
    private $orderRepository;
    private $cartServices;
    private $productServics;
    private $customer_id;

    public function __construct(orderRepository $orderRepository, cartServices $cartServices,productsServices $productsServices)
    {
        $this->orderRepository = $orderRepository;
        $this->cartServices = $cartServices;
        $this->productServics = $productsServices;

        //the customer id has to be 1 always as auto logged in user
        $this->customer_id = 1;
    }
    public function addToCart($product_id) {
        //check if product exist
        if(count($this->productServics->selectedProduct($product_id)) == 0)
            return response()->json(['error'=>'the product not exist'],404);
        else {
            $cart = $this->cartServices->customerCart($this->customer_id);

            if (count($cart) == 0) {
                $data = orderHelper::prepareCreate($this->customer_id,$product_id);
                $this->orderRepository->create($data);
                return response()->json(['success'=>true]);
            }
            else {
                //check if item added
                if(in_array($product_id,$cart['productsIds']))
                    return response()->json(['error'=>'the product already exist at the cart'],404);
                else {
                    $data = orderHelper::prepareCreate($this->customer_id,$product_id,$cart['productsIds']);
                    $this->orderRepository->update($data,$cart['id']);
                    return response()->json(['success'=>true]);
                }
            }
        }
    }
    public function customerCart() {
        $data = $this->cartServices->customerCart($this->customer_id);
        if(count($data) == 0)
            return response()->json(['error'=>'your cart is empty'],404);
        else
            return response()->json($data);
    }
    public function deleteCart() {
        $cart = $this->cartServices->customerCart($this->customer_id);
        if(count($cart) == 0)
            return response()->json(['error'=>'The selected user not has a cart'],404);
        else {
            $this->orderRepository->delete($cart['id']);
            return response()->json(['success'=>true]);
        }
    }
    public function removeProductFromOrder($product_id) {
        $cart = $this->cartServices->customerOrderByProductId($this->customer_id,$product_id);
        if(count($cart) == 0)
            return response()->json(['error'=>'The cart with this product not exist'],404);
        else {
            $items = orderHelper::removeItem($cart['productsIds'],$product_id);
            if(count($items) == 0)
                return response()->json(['error'=>'The cart contain only this product, delete the cart instead of remove the product'],404);
            else {
                $this->orderRepository->update(['items'=>json_encode($items)],$cart['id']);
                return response()->json(['success'=>true]);
            }
        }
    }
}
