<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    
    public function ok($items = null)
    {
        // return response()->json($items)->setEncodingOptions(JSON_NUMERIC_CHECK);
        return response()->json($items);
    }

    /**
     * Used to return success response
     * @return Response
     */

    public function success($items = null, $status = 200)
    {
        $data = ['status' => 'success'];

        if ($items instanceof Arrayable) {
            $items = $items->toArray();
        }

        if ($items) {
            foreach ($items as $key => $item) {
                $data[$key] = $item;
            }
        }

        // return response()->json($data, $status)->setEncodingOptions(JSON_NUMERIC_CHECK);
        return response()->json($items, $status);
    }

    /**
     * Used to return error response
     * @return Response
     */

    public function error($items = null, $status = 422)
    {
        $data = array();

        if ($items) {
            foreach ($items as $key => $item) {
                $data['errors'][$key][] = $item;
            }
        }
        throw new HttpResponseException(response()->json($items, 422));

        // return response()->json($data, $status)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }
}
