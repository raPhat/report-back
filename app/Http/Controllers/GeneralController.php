<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Image;

class GeneralController extends Controller
{
    function upload(Request $request) {
        $imageName = time().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'), $imageName);

        return response()->json($imageName);
    }
}
