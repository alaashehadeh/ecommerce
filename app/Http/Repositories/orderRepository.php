<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/4/2018
 * Time: 9:39 AM
 */

namespace App\Http\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;

class orderRepository extends BaseRepository
{
    function model()
    {
        return "App\\order";
    }
    function customerCart($customer_id) {
        return $this->model
            ->selectRaw('*,order.id as id')
            ->join('customer', 'order.customer_id', '=', 'customer.id')
            ->where('customer_id',$customer_id)->first();
    }
    function customerCartByProductId($customer_id,$product_id) {
        return $this->model
            ->selectRaw('*,order.id as id')
            ->join('customer', 'order.customer_id', '=', 'customer.id')
            ->where('customer_id',$customer_id)
            ->whereJsonContains('order.items',$product_id)->first();
    }
}