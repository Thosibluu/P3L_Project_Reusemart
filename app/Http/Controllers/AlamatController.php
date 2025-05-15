<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search', '');

        $alamats = Alamat::where('alamat_email', $user->alamat_email)
            ->where(function ($query) use ($search) {
                $query->where('id_alamat', 'LIKE', "%{$search}%")
                      ->orWhere('nama_alamat', 'LIKE', "%{$search}%")
                      ->orWhere('jenis_alamat', 'LIKE', "%{$search}%");
            })
            ->get();

        return response()->json($alamats, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_alamat' => 'required|string|max:255',
            'jenis_alamat' => 'required|in:Rumah,Kantor,Apartemen,Toko',
        ]);

        $lastId = DB::table('alamat')
            ->select(DB::raw("CAST(SUBSTRING(id_alamat, 3) AS UNSIGNED) as id_num"))
            ->orderByDesc('id_num')
            ->value('id_num') ?? 13;

        $newId = 'AR' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

        $alamat = $request->user()->alamats()->create([
            'id_alamat' => $newId,
            'nama_alamat' => $request->nama_alamat,
            'jenis_alamat' => $request->jenis_alamat,
            'status_alamat' => 'Cadangan',
        ]);

        return response()->json(['message' => 'Alamat berhasil ditambahkan.', 'data' => $alamat], 201);
    }

    public function update(Request $request, $id)
    {
        $alamat = Alamat::where('id_alamat', $id)->where('alamat_email', $request->user()->alamat_email)->firstOrFail();

        $request->validate([
            'nama_alamat' => 'required|string|max:255',
            'jenis_alamat' => 'required|in:Rumah,Kantor,Apartemen,Toko',
        ]);

        $alamat->update([
            'nama_alamat' => $request->nama_alamat,
            'jenis_alamat' => $request->jenis_alamat,
        ]);

        return response()->json(['message' => 'Alamat berhasil diperbarui.', 'data' => $alamat]);
    }

    public function destroy($id)
    {
        $alamat = Alamat::where('id_alamat', $id)->where('alamat_email', auth()->user()->alamat_email)->firstOrFail();
        $alamat->delete();

        return response()->json(['message' => 'Alamat berhasil dihapus.']);
    }

    public function setUtama($id)
    {
        $user = auth()->user();
        $user->alamats()->update(['status_alamat' => 'Cadangan']);

        $alamat = Alamat::where('id_alamat', $id)->where('alamat_email', $user->alamat_email)->firstOrFail();
        $alamat->update(['status_alamat' => 'Utama']);

        return response()->json(['message' => 'Alamat utama diperbarui.', 'data' => $alamat]);
    }

    
}