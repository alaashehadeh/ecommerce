<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/4/2018
 * Time: 9:39 AM
 */

namespace App\Http\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;

class discountRepository extends BaseRepository
{
    function model()
    {
        return "App\\products_discount";
    }
}