<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/4/2018
 * Time: 10:03 AM
 */

namespace App\Http\Helpers;


class productHelper
{
    public static function prepareCreate($data) {
        $output = array();
        $output['name'] = $data->product_name;
        $output['price'] = $data->product_price;
        $output['currency'] = $data->price_currency;
        return $output;
    }
    public static function singleProduct($product) {
        $output = array();
        $output['id'] = $product->id;
        $output['product_name'] = $product->name;
        $output['product_price'] = $product->price;
        $output['price_currency'] = $product->currency;
        return $output;
    }
    public static function productWithDiscount($product,$discount) {
        $product['original_price'] = $product['product_price'];

        //get the cost after discount
        if($discount->type == 'number')
            $discount = $product['product_price'] - $discount->amount;
        elseif($discount->type == 'percentage') {
            $discount = $discount->amount/100;
            $discount = $product['product_price']*$discount;
        }
        $product['discountAmount'] = $discount;
        $product['product_price'] = $product['product_price'] - $discount;
        return $product;
    }
    public static function multiProducts($products) {
        $output = array();
        $total = array();
        foreach ($products as $key => $value) {
            $output[$key] = $value;
            if($value->amount > 0) {
                $discount = array('amount' => $value->amount, 'type' => $value->type);
                $output[$key] = self::productWithDiscount($value, (object)$discount);
            }
            //get the total by currency
            if (!@$total[$value->currency])
                $total[$value->currency] = 0;

            $total[$value->currency] = $total[$value->currency] + $value->price;
        }
        return array('products'=>$output,'total'=>$total);
    }
    public static function searchOutput($products) {
        $output = array();
        foreach ($products as $key=>$value) {
            $output[$key] = $value;
            if($value->amount > 0) {
                $discount = array('amount' => $value->amount, 'type' => $value->type);
                $product = self::singleProduct($value);
                $output[$key] = self::productWithDiscount($product, (object)$discount);
            }
        }
        return $output;
    }
}