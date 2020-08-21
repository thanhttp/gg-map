<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use Illuminate\Http\Request;

class CoordinatesController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        event(new SendMessage($data));

        return response()->json([
            'code' => 200,
            'message' => 'thành công',
        ]);
    }

    public function pull(Request $request)
    {
        
    }
}
