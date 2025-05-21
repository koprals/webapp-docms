<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPermohonan;

class HomepageController extends Controller
{
    public function index() {

        $this->data['jenis_permohonan'] = JenisPermohonan::get();

        return view('homepage', $this->data);
    }
}
