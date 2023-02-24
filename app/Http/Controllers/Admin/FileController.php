<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function upload(Request $request)
    {

        $request -> validate([
            'file' => 'required|mimes:jpg,bmp,png|max:2048'
        ]);
        $file = $request -> file('file');
        $modelType = $request -> input('model_type');
        $modelId = $request -> input('model_id');
        $originalName = $file -> getClientOriginalName();
        $name = now() -> format('Ymd') . base64_encode(Str ::random(8) . time()) . '.' . $file -> extension();
        if ($fileData = File ::query() -> where('original_name', $originalName) -> first()) {
            Storage ::disk('upload') -> delete($fileData -> name);
            $path = $file -> storeAs('upload', $name);
            $fileData -> name = $name;
            $fileData -> path = $path;
            $fileData -> extension = $file -> extension();
            $fileData -> model_id = $modelId;
            $fileData -> model_type = $modelType;
            $fileData -> save();
        } else {
            $path = $file -> storeAs('upload', $name);
            File ::query() -> create([
                'original_name' => $originalName,
                'name' => $name,
                'extension' => $file -> extension(),
                'model_type' => $modelType,
                'model_id' => $modelId,
                'path' => $path,
            ]);
        }
        return $this -> responseSuccess($path);
    }
}
