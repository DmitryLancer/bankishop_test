<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ZipArchive;

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

    public function showImages(Request $request)
    {
        $sort = $request->input('sort');
        $direction = $request->input('direction', 'asc');

        $query = Image::query();

        if ($sort) {
            $query->orderBy($sort, $direction);
        }

        $images = $query->get();

        $nextDirection = ($direction === 'asc') ? 'desc' : 'asc';

        return view('images.show', compact('images', 'nextDirection'));
    }

    public function downloadImage($id) {
        $image = Image::find($id);
        $zipFileName = $image->filename . '.zip';
        $zip = new ZipArchive;
        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            $zip->addFile(public_path('uploads/'.$image->filename), $image->filename);
            $zip->close();
        }

        return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
    }

    public function getImagesJson() {
        $images = Image::all();
        return response()->json($images);
    }

    public function getImageJson($id) {
        $image = Image::find($id);
        if ($image) {
            return response()->json($image);
        } else {
            return response()->json(['message' => 'Image not found'], 404);
        }
    }


}
