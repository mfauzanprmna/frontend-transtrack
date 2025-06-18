<?php

namespace App\Http\Controllers;

use App\Dataset;
use App\Exports\DatasetExport;
use App\file_dataset;
use App\Imports\DatasetImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Reader;
use RealRashid\SweetAlert\Facades\Alert;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = file_dataset::paginate(20);


        return view('basic.index', compact('datasets'));
    }

    public function getFamilyValues(Request $request)
    {
        $filePath = $request->input('file_path');
        $columnName = $request->input('column');

        if (!$filePath || !$columnName) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $handle = fopen($fullPath, 'r');
        if (!$handle) {
            return response()->json(['error' => 'Failed to open file'], 500);
        }

        $headers = fgetcsv($handle);
        $columnIndex = array_search($columnName, $headers);

        if ($columnIndex === false) {
            fclose($handle);
            return response()->json(['error' => 'Column not found'], 400);
        }

        $unique = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (isset($row[$columnIndex])) {
                $val = trim($row[$columnIndex]);
                if ($val !== '' && !isset($unique[$val])) {
                    $unique[$val] = true;
                }
            }
        }

        fclose($handle);
        return response()->json(array_keys($unique));
    }

    public function create()
    {
        $latest = Dataset::latest()->first();
        $keys = $latest ? array_keys($latest->data) : [];

        return view('basic.create', compact('keys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_col' => 'required|string',
            'sales_col' => 'required|string',
        ]);

        try {
            $data = [
                'file_name' => $request->input('name', 'train.csv'),
                'date_column' => $request->input('date_col'),
                'sales_column' => $request->input('sales_col'),
                'store_column' =>  $request->input('store_col'),
                'family_column' => $request->input('family_col'),
            ];

            // Simpan data ke database
            file_dataset::create($data);

            Alert::success('Berhasil', 'Configuration saved.');
            return redirect()->route('dataset.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to create dataset: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $dataset = Dataset::findOrFail($id);
        return view('basic.edit', compact('dataset'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keys' => 'required|array',
            'values' => 'required|array',
        ]);

        try {
            $data = array_combine($request->keys, $request->values);

            $dataset = Dataset::findOrFail($id);
            $dataset->data = $data;
            $dataset->save();

            return redirect()->route('dataset.index')->with('success', 'Data updated');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $dataset = Dataset::findOrFail($id);
            $dataset->delete();

            return redirect()->route('dataset.index')->with('success', 'Data deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete data: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'file' => 'required|mimes:csv,txt,xlsx,xls'
        ]);

        $filename = $request->name . '.' . $request->file('file')->getClientOriginalExtension();
        $path = 'datasets/' . $filename;

        // Cek apakah file sudah ada
        if (storage::exists($path)) {
            // Kirim notifikasi ke view untuk minta konfirmasi
            return back()->with([
                'filename_conflict' => $filename,
                'message' => 'File dengan nama tersebut sudah ada. Ganti nama atau update dataset nya.'
            ]);
        }
        // Simpan file (replace jika ada)
        $request->file('file')->storeAs('datasets', $filename);

        // Ambil header CSV
        $fullPath = storage_path('app/public/' . $path);
        $headers = [];

        if (($handle = fopen($fullPath, 'r')) !== false) {
            $headers = fgetcsv($handle);
            fclose($handle);
        }

        return view('basic.index', [
            'headers' => $headers,
            'filename' => $path,
        ]);
    }

    public function export()
    {
        return Excel::download(new DatasetExport, 'dataset.xlsx');
    }
}
