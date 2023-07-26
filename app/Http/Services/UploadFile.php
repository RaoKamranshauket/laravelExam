<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFile
{
    /**
     * If the uploaded file name matches with the name of the file in the uploaded directory,
     * the methods overrides the current file with the new file.
     *
     * @param UploadedFile $uploadedFile
     * @param string       $path
     * @param string       $fileSystem
     * @param bool         $assignNewName
     *
     * @return false|string
     * @throws \Exception
     */

    public function fileUpload(uploadedFile $uploadedFile, $path = 'uploads', $assignNewName = true, $fileSystem = 'public')
    {
        if ($assignNewName) {
            $extension = $uploadedFile->getClientOriginalExtension();
            $fileName = sprintf('%s.%s', Str::random(6) . strtotime(now()), $extension);
        } else {
            $fileName = $uploadedFile->getClientOriginalName();
        }
       // try {
        Storage::disk('local')->putFileAs(
            'uploads/'.$fileName,
            $uploadedFile,
            $fileName
          );
            // $uploadedFile->storeAs(
            //     $path,
            //     $fileName,
            //     $fileSystem
            // );
            Log::info($path.$fileName);
            return $fileName;

       // } catch (\Exception$e) {
           // throw new \Exception($e);
       // }
    }
}
