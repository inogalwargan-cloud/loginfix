
@php
    $suratKeluar = $suratKeluar ?? null;
@endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    <div>
        <label for="nomor_agenda" class="block text-sm font-medium text-gray-700">
            Nomor Agenda *
        </label>
        <input id="nomor_agenda" name="nomor_agenda"
            value="{{ old('nomor_agenda', $suratKeluar?->nomor_agenda) }}"
            required maxlength="255"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('nomor_agenda')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nomor_surat" class="block text-sm font-medium text-gray-700">
            Nomor Surat
        </label>
        <input id="nomor_surat" name="nomor_surat"
            value="{{ old('nomor_surat', $suratKeluar?->nomor_surat) }}"
            maxlength="255"
            placeholder="Kosongkan jika masih draft"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('nomor_surat')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tanggal_surat" class="block text-sm font-medium text-gray-700">
            Tanggal Surat *
        </label>
        <input type="date" id="tanggal_surat" name="tanggal_surat"
            value="{{ old('tanggal_surat', $suratKeluar?->tanggal_surat?->format('Y-m-d')) }}"
            required
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">
        @error('tanggal_surat')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tujuan" class="block text-sm font-medium text-gray-700">
            Tujuan / Penerima *
        </label>
        <input id="tujuan" name="tujuan"
            value="{{ old('tujuan', $suratKeluar?->tujuan) }}"
            required maxlength="255"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">
        @error('tujuan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="perihal" class="block text-sm font-medium text-gray-700">
            Perihal *
        </label>
        <input id="perihal" name="perihal"
            value="{{ old('perihal', $suratKeluar?->perihal) }}"
            required maxlength="255"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">
        @error('perihal')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sifat" class="block text-sm font-medium text-gray-700">
            Sifat Surat *
        </label>
        <select id="sifat" name="sifat" required
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">
            @foreach (['biasa' => 'Biasa', 'penting' => 'Penting', 'segera' => 'Segera', 'rahasia' => 'Rahasia'] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('sifat', $suratKeluar?->sifat ?? 'biasa') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('sifat')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">
            Status Surat *
        </label>
        <select id="status" name="status" required
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">
            @foreach (['draft' => 'Draft', 'proses' => 'Proses', 'terkirim' => 'Terkirim'] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('status', $suratKeluar?->status ?? 'draft') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="isi_surat" class="block text-sm font-medium text-gray-700">
            Isi Surat
        </label>
        <textarea id="isi_surat" name="isi_surat" rows="7"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">{{ old('isi_surat', $suratKeluar?->isi_surat) }}</textarea>
        @error('isi_surat')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="lampiran" class="block text-sm font-medium text-gray-700">
            Lampiran PDF (maksimal 5 MB)
        </label>
        <input type="file" id="lampiran" name="lampiran"
            accept=".pdf,application/pdf"
            class="mt-1 block w-full text-sm text-gray-600">
        @error('lampiran')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($suratKeluar?->file_path)
            <p class="mt-2 text-sm text-gray-600">
                <a href="{{ route('surat-keluar.lampiran', $suratKeluar) }}"
                    class="text-indigo-600 hover:underline">Unduh lampiran saat ini</a>
            </p>
            <p class="text-xs text-gray-500">
                Kosongkan jika tidak ingin mengganti lampiran.
            </p>
        @endif
    </div>

</div>

<div class="mt-6 flex gap-3">
    <button type="submit"
        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
        {{ $suratKeluar ? 'Simpan Perubahan' : 'Simpan Surat' }}
    </button>

    <a href="{{ route('surat-keluar.index') }}"
        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700">
        Batal
    </a>
</div>
