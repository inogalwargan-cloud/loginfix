<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DisposisiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $disposisis = Disposisi::with([
            'suratMasuk',
            'penerima',
            'pemberi',
        ])
            ->when(
                $user->isRole('staf'),
                fn ($query) => $query->where('penerima_id', $user->id)
            )
            ->latest()
            ->paginate(10);

        return view('disposisi.index', compact('disposisis'));
    }

    public function create(SuratMasuk $suratMasuk)
    {
        abort_unless(
            auth()->user()->isRole('admin', 'pimpinan'),
            403
        );

        $staf = User::where('role', 'staf')
            ->orderBy('name')
            ->get();

        return view('disposisi.create', compact('suratMasuk', 'staf'));
    }

    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(
            $request->user()->isRole('admin', 'pimpinan'),
            403
        );

        $validated = $request->validate([
            'penerima_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'staf'),
            ],
            'instruksi' => ['required', 'string', 'max:5000'],
            'batas_waktu' => ['nullable', 'date'],
        ]);

        $validated['surat_masuk_id'] = $suratMasuk->id;
        $validated['pemberi_id'] = $request->user()->id;
        $validated['status'] = 'baru';

        Disposisi::create($validated);

        $suratMasuk->update(['status' => 'diproses']);

        return redirect()
            ->route('disposisi.index')
            ->with('success', 'Disposisi berhasil diberikan.');
    }

    public function update(Request $request, Disposisi $disposisi)
    {
        $user = $request->user();

        abort_unless(
            $user->isRole('admin', 'pimpinan')
                || (
                    $user->isRole('staf')
                    && $disposisi->penerima_id === $user->id
                ),
            403
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['baru', 'diproses', 'selesai']),
            ],
            'catatan_hasil' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($user->isRole('staf')) {
            abort_if(
                $disposisi->status === 'selesai',
                403,
                'Disposisi yang sudah selesai tidak dapat diubah oleh staf.'
            );

            // Staf hanya dapat menyelesaikan atau memproses tugasnya.
            abort_unless(
                in_array($validated['status'], ['diproses', 'selesai'], true),
                422
            );
        }

        DB::transaction(function () use ($disposisi, $validated) {
            $selesai = $validated['status'] === 'selesai';

            $disposisi->update([
                'status' => $validated['status'],
                'catatan_hasil' => $validated['catatan_hasil'] ?? null,
                'selesai_pada' => $selesai ? now() : null,
            ]);

            $masihAktif = Disposisi::where(
                'surat_masuk_id',
                $disposisi->surat_masuk_id
            )
                ->where('status', '!=', 'selesai')
                ->exists();

            $disposisi->suratMasuk->update([
                'status' => $masihAktif ? 'diproses' : 'selesai',
            ]);
        });

        return redirect()
            ->route('disposisi.index')
            ->with('success', 'Status disposisi berhasil diperbarui.');
    }
}
