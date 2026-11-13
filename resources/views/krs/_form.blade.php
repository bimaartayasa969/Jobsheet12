<div class="mb-3">
    <label for="mahasiswa_id" class="form-label">Mahasiswa</label>
    <select id="mahasiswa_id" name="mahasiswa_id"
        @class(['form-select', 'is-invalid' => $errors->has('mahasiswa_id')])>
        <option value="">-- Pilih Mahasiswa --</option>
        @foreach ($daftarMahasiswa as $m)
            <option value="{{ $m->id }}"
                @selected((string) old('mahasiswa_id', $krs->mahasiswa_id) === (string) $m->id)>
                {{ $m->nim }} - {{ $m->nama }}
            </option>
        @endforeach
    </select>
    @error('mahasiswa_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <x-form-input label="Tahun Akademik (contoh: 2025/2026)" name="tahun_akademik"
                  :value="$krs->tahun_akademik" maxlength="9" required />
</div>

<div class="mb-3">
    <label for="semester" class="form-label">Semester</label>
    <select id="semester" name="semester"
        @class(['form-select', 'is-invalid' => $errors->has('semester')])>
        <option value="">-- Pilih --</option>
        @foreach (['Ganjil', 'Genap'] as $opsi)
            <option value="{{ $opsi }}" @selected(old('semester', $krs->semester) === $opsi)>{{ $opsi }}</option>
        @endforeach
    </select>
    @error('semester')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select id="status" name="status"
        @class(['form-select', 'is-invalid' => $errors->has('status')])>
        @foreach (['draft', 'diajukan', 'disetujui', 'ditolak'] as $opsi)
            <option value="{{ $opsi }}" @selected(old('status', $krs->status ?? 'draft') === $opsi)>{{ ucfirst($opsi) }}</option>
        @endforeach
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label d-block">Mata Kuliah yang Diambil</label>
    <div @class(['border rounded p-2', 'border-danger' => $errors->has('mata_kuliah_id') || $errors->has('mata_kuliah_id.*')])
         style="max-height: 260px; overflow-y: auto;">
        @foreach ($daftarMataKuliah as $mk)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="mata_kuliah_id[]"
                       id="mk_{{ $mk->id }}" value="{{ $mk->id }}"
                       @checked(in_array($mk->id, array_map('intval', (array) old('mata_kuliah_id', $terpilih))))>
                <label class="form-check-label" for="mk_{{ $mk->id }}">
                    {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)
                </label>
            </div>
        @endforeach
    </div>
    @error('mata_kuliah_id')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
    @error('mata_kuliah_id.*')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>