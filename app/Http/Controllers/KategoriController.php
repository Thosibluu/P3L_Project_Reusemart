<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        $barangs = []; // Fetch barangs if needed, e.g., Barang::all();
        return view('index', compact('kategoris', 'barangs'));
    }

    // Optional: Add a method to check image existence if needed
    private function imageExists($id)
    {
        return file_exists(public_path('img/' . $id . '.jpg'));
    }
}
