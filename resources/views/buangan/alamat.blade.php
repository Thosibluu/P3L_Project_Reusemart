<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pembeli - Reusemart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Daftar Alamat Pengiriman</h2>
    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#alamatModal" onclick="bukaModalCreate()">+ Tambah Alamat</button>

    {{-- Tabel alamat --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Alamat</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($alamat as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->nama_alamat }}</td>
                <td>{{ $a->tipe }}</td>
                <td>
                    @if($a->status == 'Utama')
                        <span class="badge bg-success">Utama</span>
                    @else
                        <span class="badge bg-secondary">Cadangan</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="bukaModalEdit('{{ $a->id }}', '{{ $a->nama_alamat }}', '{{ $a->tipe }}')">Edit</button>
                    <form action="{{ url('/alamat/' . $a->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus alamat ini?')">Hapus</button>
                    </form>
                    @if($a->status != 'Utama')
                        <form action="{{ url('/alamat/' . $a->id . '/utama') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">Jadikan Utama</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- Modal Tambah/Edit Alamat --}}
<div class="modal fade" id="alamatModal" tabindex="-1" aria-labelledby="alamatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="alamatForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="alamatModalLabel">Tambah Alamat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_alamat" class="form-label">Nama Alamat</label>
                        <input type="text" name="nama_alamat" id="nama_alamat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipe" class="form-label">Tipe Alamat</label>
                        <select name="tipe" id="tipe" class="form-select" required>
                            <option value="Rumah">Rumah</option>
                            <option value="Kantor">Kantor</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
                @method('POST') {{-- default --}}
            </form>
        </div>
    </div>
</div>

<script>
    // Buat modal tambah
    function bukaModalCreate() {
        const form = document.getElementById('alamatForm');
        form.action = '/alamat';
        form.querySelector('[name="_method"]').value = 'POST';
        document.getElementById('alamatModalLabel').innerText = 'Tambah Alamat';
        form.reset();
    }

    // Buat modal edit
    function bukaModalEdit(id, nama, tipe) {
        const form = document.getElementById('alamatForm');
        form.action = '/alamat/' + id;
        form.querySelector('[name="_method"]').value = 'PUT';
        document.getElementById('alamatModalLabel').innerText = 'Edit Alamat';

        document.getElementById('nama_alamat').value = nama;
        document.getElementById('tipe').value = tipe;

        const modal = new bootstrap.Modal(document.getElementById('alamatModal'));
        modal.show();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>