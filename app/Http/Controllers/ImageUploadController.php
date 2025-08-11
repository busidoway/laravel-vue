<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {

        // return response()->json(['location' => $request], 200);

        // Валидация файла
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ограничение на тип и размер файла
        ]);

        // Получение загруженного файла
        $file = $request->file('file');

        // Создание уникального имени файла
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Сохранение файла в папке "uploads"
        $filePath = $file->storeAs('images/uploads', $fileName, 'public');

        // Получение полного URL к сохраненному файлу
        $fileUrl = URL::to('/') . Storage::url($filePath);

        // Возврат URL файла в ответе
        return response()->json(['location' => $fileUrl, 'filePath' => $filePath], 200);
        // return ['location' => $fileUrl];
    }
}
