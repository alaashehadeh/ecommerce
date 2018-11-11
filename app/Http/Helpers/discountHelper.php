<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/4/2018
 * Time: 10:03 AM
 */

namespace App\Http\Helpers;


class discountHelper
{
    public static function prepareCreate($data) {
        $output = array();
        $output['type'] = $data->discount_type;
        $output['amount'] = $data->discount_amount;
        $output['product_id'] = $data->product_id;
        return $output;
    }
}