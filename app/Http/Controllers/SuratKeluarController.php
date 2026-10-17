<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $suratKeluars = SuratKeluar::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_agenda', 'like', "%{$search}%")
                        ->orWhere('nomor_surat', 'like', "%{$search}%")
                        ->orWhere('tujuan', 'like', "%{$search}%")
                        ->orWhere('perihal', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat-keluar.index', compact(
            'suratKeluars',
            'search'
        ));
    }

    public function create()
    {
        return view('surat-keluar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_agenda' => [
                'required', 'string', 'max:255',
                'unique:surat_keluars,nomor_agenda',
            ],
            'nomor_surat' => ['nullable', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tujuan' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'isi_surat' => ['nullable', 'string'],
            'sifat' => [
                'required',
                Rule::in(['biasa', 'penting', 'segera', 'rahasia']),
            ],
            'status' => [
                'required',
                Rule::in(['draft', 'proses', 'terkirim']),
            ],
            'lampiran' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        if ($request->hasFile('lampiran')) {
            $validated['file_path'] = $request->file('lampiran')
                ->store('surat-keluar', 'local');
        }

        unset($validated['lampiran']);

        $validated['created_by'] = $request->user()->id;

        SuratKeluar::create($validated);

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        return view('surat-keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        return view('surat-keluar.edit', compact('suratKeluar'));
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $validated = $request->validate([
            'nomor_agenda' => [
                'required', 'string', 'max:255',
                Rule::unique('surat_keluars', 'nomor_agenda')
                    ->ignore($suratKeluar->id),
            ],
            'nomor_surat' => ['nullable', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'tujuan' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'isi_surat' => ['nullable', 'string'],
            'sifat' => [
                'required',
                Rule::in(['biasa', 'penting', 'segera', 'rahasia']),
            ],
            'status' => [
                'required',
                Rule::in(['draft', 'proses', 'terkirim']),
            ],
            'lampiran' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        if ($request->hasFile('lampiran')) {
            if ($suratKeluar->file_path) {
                Storage::disk('local')->delete($suratKeluar->file_path);
            }

            $validated['file_path'] = $request->file('lampiran')
                ->store('surat-keluar', 'local');
        }

        unset($validated['lampiran']);

        $suratKeluar->update($validated);

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function lampiran(SuratKeluar $suratKeluar)
    {
        abort_unless(
            $suratKeluar->file_path
                && Storage::disk('local')->exists($suratKeluar->file_path),
            404
        );

        return Storage::disk('local')->download(
            $suratKeluar->file_path,
            'lampiran-surat-keluar-' . $suratKeluar->id . '.pdf'
        );
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->file_path) {
            Storage::disk('local')->delete($suratKeluar->file_path);
        }

        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }
}
