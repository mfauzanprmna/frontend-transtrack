<?php

namespace App\Http\Controllers;

use App\file_dataset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;


class ForecastController extends Controller
{
    public function index(Request $request)
    {
        $data = file_dataset::paginate(20);

        return view('prediksi.restock', compact('data'));
    }
}
