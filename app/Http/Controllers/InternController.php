<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intern;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class InternController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function uploadInternData(Request $request)
    {

        // dd($request);

        // Проверяем, был ли отправлен файл
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'Файл не был загружен'], 400);
        }

        $file = $request->file('file');

        // Проверяем, является ли это действительно файлом
        if (!$file->isValid()) {
            return response()->json(['error' => 'Ошибка загрузки файла'], 400);
        }

        // Опциональная валидация
        $request->validate([
            'file' => 'required|mimes:xlsx' // Ограничение по типу и размеру (2MB)
        ]);

        $inputFileType = 'Xlsx';

        $inputFileName = $request->file('file');

        $reader = IOFactory::createReader($inputFileType);

        $reader->setReadEmptyCells(false);

        $spreadsheet = $reader->load($inputFileName);

        $sheetData = $spreadsheet->getActiveSheet()->toArray("", true, true, true);

        array_shift($sheetData);

        if($request->check === true || $request->check === "true"){
            Intern::truncate();
        }

        $upload_rows = [];
        $invalid_rows = [];

        foreach ($sheetData as $key=>$data) {

            if (!empty($data['A']) && !empty($data['B'])) {

                // Валидация входящих данных
                $validated = Validator::make($data, [
                    'E' => [
                        'required',
                        'email',
                        function ($attribute, $value, $fail) {
                            if (str_contains($value, ' ')) {
                                $fail('Адрес электронной почты содержит недопустимые пробелы.');
                            }
                        },
                    ]
                ]);

                if ($validated->fails()) {
                    $invalid_rows[] = [
                        'row' => $key + 2,
                        'email' => $data['E'] ?? null,
                        'errors' => $validated->errors()->all()
                    ];
                    continue;
                } else {
                    $upload_rows[] = [
                        'row' => $key + 1
                    ];
                }

                $intern = Intern::create([
                    'last_name' => $data['A'],
                    'name' => $data['B'],
                    'surname' => $data['C'],
                    'phone' => trim($data['D']),
                    'email' => trim($data['E']),
                    'position' => $data['F']
                ]);

            }

        }

        $upload_rows_count = count($upload_rows);
        $invalid_rows_count = count($invalid_rows);

        return response()->json([
            'status' => true,
            'invalid_rows' => $invalid_rows,
            'upload_rows_count' => $upload_rows_count,
            'invalid_rows_count' => $invalid_rows_count
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
