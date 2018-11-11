<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/4/2018
 * Time: 10:03 AM
 */

namespace App\Http\Helpers;


class orderHelper
{
    public static function prepareCreate($customer_id,$product_id,$items = []) {
        $output = array();
        $output['customer_id'] = $customer_id;
        $output['purchased'] = false;
        $items[] = $product_id;
        $output['items'] = json_encode($items);
        return $output;
    }
    public static function selectedCart($cart,$products = [],$total = []) {
        $output = array();
        if($cart) {
            $output['id'] = $cart->id;
            $output['customer_name'] = $cart->name;
            $output['purchased'] = ($cart->purchased) ? 'yes' : 'No';
            $output['productsIds'] = json_decode($cart->items);
            $output['products'] = $products;
            $output['total'] = $total;
        }
        return $output;
    }
    public static function removeItem($items,$item_id) {
        $key = array_search($item_id,$items);
        unset($items[$key]);
        $items = array_values($items);
        return $items;
    }
}