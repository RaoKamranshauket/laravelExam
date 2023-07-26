<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
//use Spatie\FlareClient\Http\Response;

class FileController extends Controller
{
    public function view($file)
    {
        $filePath = public_path('uploads/' . $file);

        if (file_exists($filePath)) {
            $fileContents = file_get_contents($filePath);
            $mimeType = Storage::mimeType($filePath);

            return Response::make($fileContents, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $file . '"',
            ]);
        } else {
            abort(404, 'File not found');
        }
    }
}
