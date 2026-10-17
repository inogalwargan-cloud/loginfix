<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $suratMasuks = SuratMasuk::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_agenda', 'like', "%{$search}%")
                        ->orWhere('nomor_surat', 'like', "%{$search}%")
                        ->orWhere('pengirim', 'like', "%{$search}%")
                        ->orWhere('perihal', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat-masuk.index', compact('suratMasuks', 'search'));
    }

    public function create()
    {
        return view('surat-masuk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_agenda' => ['required', 'string', 'max:255', 'unique:surat_masuks,nomor_agenda'],
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_diterima' => ['required', 'date'],
            'pengirim' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'isi_ringkas' => ['nullable', 'string'],
            'sifat' => ['required', Rule::in(['biasa', 'penting', 'segera', 'rahasia'])],
            'status' => ['required', Rule::in(['baru', 'diproses', 'selesai'])],
            'lampiran' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        if ($request->hasFile('lampiran')) {
            $validated['file_path'] = $request->file('lampiran')
                ->store('surat-masuk', 'public');
        }

        unset($validated['lampiran']);

        $validated['created_by'] = $request->user()->id;

        SuratMasuk::create($validated);

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_agenda' => [
                'required',
                'string',
                'max:255',
                Rule::unique('surat_masuks', 'nomor_agenda')
                    ->ignore($suratMasuk->id),
            ],
            'nomor_surat' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_diterima' => ['required', 'date'],
            'pengirim' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'isi_ringkas' => ['nullable', 'string'],
            'sifat' => ['required', Rule::in(['biasa', 'penting', 'segera', 'rahasia'])],
            'status' => ['required', Rule::in(['baru', 'diproses', 'selesai'])],
            'lampiran' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        if ($request->hasFile('lampiran')) {
            if ($suratMasuk->file_path) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }

            $validated['file_path'] = $request->file('lampiran')
                ->store('surat-masuk', 'public');
        }

        unset($validated['lampiran']);

        $suratMasuk->update($validated);

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_path) {
            Storage::disk('public')->delete($suratMasuk->file_path);
        }

        $suratMasuk->delete();

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    
public function lampiran(SuratMasuk $suratMasuk)
{
    abort_unless(
        $suratMasuk->file_path
            && Storage::disk('public')->exists($suratMasuk->file_path),
        404
    );

    return Storage::disk('public')->download(
        $suratMasuk->file_path,
        'lampiran-surat-' . $suratMasuk->id . '.pdf'
    );
}

}
