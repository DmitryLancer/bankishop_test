<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function showUploadForm()
    {
        return view('images.upload');
    }


    public function uploadImage(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image',
        ]);

        $images = $request->file('images');
        if(count($images) > 5) {
            return 'Вы можете загрузить до 5 изображений одновременно';
        }

        foreach ($images as $image) {
            $imageName = strtolower(Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $i = 1;
            while (file_exists(public_path('uploads/' . $imageName))) {
                $imageName = strtolower(transliterator_transliterate('Any-Latin; Latin-ASCII', pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))) . '_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                $i++;
            }

            $image->move(public_path('uploads'), $imageName);

            $imageModel = new Image();
            $imageModel->filename = $imageName;
            $imageModel->upload_time = now();
            $imageModel->save();
        }

        return 'Images uploaded successfully!';
    }

}
