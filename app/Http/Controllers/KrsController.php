<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class KrsController extends Controller
{
    private const MAKS_SKS = 24;
    private const STATUS = ['draft', 'diajukan', 'disetujui', 'ditolak'];

    public function index(): View
    {
        return view('krs.index');
    }

    public function data(): JsonResponse
    {
        $query = Krs::query()
            ->join('mahasiswa', 'mahasiswa.id', '=', 'krs.mahasiswa_id')
            ->select([
                'krs.id', 'krs.tahun_akademik', 'krs.semester', 'krs.status',
                'mahasiswa.nim', 'mahasiswa.nama',
            ])
            ->withCount('mataKuliah')
            ->withSum('mataKuliah as total_sks', 'sks');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('total_sks', fn (Krs $krs) => $krs->total_sks ?? 0)
            ->editColumn('status', fn (Krs $krs) => ucfirst($krs->status))
            ->addColumn('aksi', fn (Krs $krs) => view('krs._aksi', compact('krs'))->render())
            ->rawColumns(['aksi'])
            ->toJson();
    }

    public function create(): View
    {
        return view('krs.create', $this->formData(new Krs(['status' => 'draft'])));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $krs = DB::transaction(function () use ($data) {
            $krs = Krs::create(Arr::except($data, ['mata_kuliah_id']));
            $krs->mataKuliah()->sync($data['mata_kuliah_id']);

            return $krs;
        });

        return redirect()
            ->route('krs.index')
            ->with('success', 'KRS ' . $krs->mahasiswa->nama . ' berhasil ditambahkan.');
    }

    public function show(Krs $krs): View
    {
        $krs->load(['mahasiswa', 'mataKuliah']);

        return view('krs.show', compact('krs'));
    }

    public function edit(Krs $krs): View
    {
        return view('krs.edit', $this->formData($krs));
    }

    public function update(Request $request, Krs $krs): RedirectResponse
    {
        $data = $this->validated($request, $krs);

        DB::transaction(function () use ($krs, $data) {
            $krs->update(Arr::except($data, ['mata_kuliah_id']));
            $krs->mataKuliah()->sync($data['mata_kuliah_id']);
        });

        return redirect()
            ->route('krs.index')
            ->with('success', 'KRS ' . $krs->mahasiswa->nama . ' berhasil diperbarui.');
    }

    public function destroy(Krs $krs): JsonResponse
    {
        try {
            $krs->delete(); // krs_detail ikut terhapus (cascade)
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'KRS tidak dapat dihapus: masih dipakai data lain.',
            ], 409);
        }

        return response()->json(['message' => 'KRS berhasil dihapus.']);
    }

    /** Data untuk dropdown mahasiswa dan pilihan mata kuliah di form. */
    private function formData(Krs $krs): array
    {
        return [
            'krs' => $krs,
            'daftarMahasiswa' => Mahasiswa::orderBy('nama')->get(['id', 'nim', 'nama']),
            'daftarMataKuliah' => MataKuliah::orderBy('nama_mk')->get(['id', 'kode_mk', 'nama_mk', 'sks']),
            'terpilih' => $krs->exists ? $krs->mataKuliah->pluck('id')->all() : [],
        ];
    }

    /** Validasi bersama untuk store dan update, termasuk batas SKS. */
    private function validated(Request $request, ?Krs $krs = null): array
    {
        $data = $request->validate([
            'mahasiswa_id' => ['required', 'exists:mahasiswa,id',
                Rule::unique('krs')
                    ->where(fn ($q) => $q
                        ->where('tahun_akademik', $request->input('tahun_akademik'))
                        ->where('semester', $request->input('semester')))
                    ->ignore($krs?->id)],
            'tahun_akademik' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'status' => ['required', Rule::in(self::STATUS)],
            'mata_kuliah_id' => ['required', 'array', 'min:1'],
            'mata_kuliah_id.*' => ['integer', 'distinct', 'exists:mata_kuliah,id'],
        ], [
            'mahasiswa_id.required' => 'Mahasiswa wajib dipilih.',
            'mahasiswa_id.exists' => 'Mahasiswa tidak ditemukan.',
            'mahasiswa_id.unique' => 'KRS mahasiswa ini untuk tahun akademik dan semester tersebut sudah ada.',
            'tahun_akademik.required' => 'Tahun akademik wajib diisi.',
            'tahun_akademik.regex' => 'Format tahun akademik harus seperti 2025/2026.',
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'mata_kuliah_id.required' => 'Pilih minimal satu mata kuliah.',
            'mata_kuliah_id.min' => 'Pilih minimal satu mata kuliah.',
            'mata_kuliah_id.*.distinct' => 'Mata kuliah yang sama tidak boleh dipilih dua kali.',
            'mata_kuliah_id.*.exists' => 'Ada mata kuliah yang tidak ditemukan.',
        ]);

        // Fitur tambahan: validasi batas SKS
        $totalSks = (int) MataKuliah::whereIn('id', $data['mata_kuliah_id'])->sum('sks');

        if ($totalSks > self::MAKS_SKS) {
            throw ValidationException::withMessages([
                'mata_kuliah_id' => 'Total SKS ' . $totalSks . ' melebihi batas ' . self::MAKS_SKS . ' SKS.',
            ]);
        }

        return $data;
    }
}