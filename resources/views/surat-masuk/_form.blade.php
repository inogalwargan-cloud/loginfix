
@php
    $suratMasuk = $suratMasuk ?? null;
@endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    <div>
        <label for="nomor_agenda" class="block text-sm font-medium text-gray-700">
            Nomor Agenda *
        </label>
        <input id="nomor_agenda" name="nomor_agenda"
            value="{{ old('nomor_agenda', $suratMasuk?->nomor_agenda) }}"
            required maxlength="255"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('nomor_agenda')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="nomor_surat" class="block text-sm font-medium text-gray-700">
            Nomor Surat *
        </label>
        <input id="nomor_surat" name="nomor_surat"
            value="{{ old('nomor_surat', $suratMasuk?->nomor_surat) }}"
            required maxlength="255"
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
            value="{{ old('tanggal_surat', $suratMasuk?->tanggal_surat?->format('Y-m-d')) }}"
            required
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('tanggal_surat')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tanggal_diterima" class="block text-sm font-medium text-gray-700">
            Tanggal Diterima *
        </label>
        <input type="date" id="tanggal_diterima" name="tanggal_diterima"
            value="{{ old('tanggal_diterima', $suratMasuk?->tanggal_diterima?->format('Y-m-d')) }}"
            required
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('tanggal_diterima')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="pengirim" class="block text-sm font-medium text-gray-700">
            Pengirim *
        </label>
        <input id="pengirim" name="pengirim"
            value="{{ old('pengirim', $suratMasuk?->pengirim) }}"
            required maxlength="255"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('pengirim')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="perihal" class="block text-sm font-medium text-gray-700">
            Perihal *
        </label>
        <input id="perihal" name="perihal"
            value="{{ old('perihal', $suratMasuk?->perihal) }}"
            required maxlength="255"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                    @selected(old('sifat', $suratMasuk?->sifat ?? 'biasa') === $value)>
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
            Status *
        </label>
        <select id="status" name="status" required
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">
            @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('status', $suratMasuk?->status ?? 'baru') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="isi_ringkas" class="block text-sm font-medium text-gray-700">
            Isi Ringkas
        </label>
        <textarea id="isi_ringkas" name="isi_ringkas" rows="4"
            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">{{ old('isi_ringkas', $suratMasuk?->isi_ringkas) }}</textarea>
        @error('isi_ringkas')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="lampiran" class="block text-sm font-medium text-gray-700">
            Lampiran PDF (maksimal 5 MB)
        </label>
        <input type="file" id="lampiran" name="lampiran" accept=".pdf,application/pdf"
            class="mt-1 block w-full text-sm text-gray-600">
        @error('lampiran')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($suratMasuk?->file_path)
            <p class="mt-2 text-sm text-gray-600">
                Lampiran saat ini:
                <a href="{{ route('surat-masuk.lampiran', $suratMasuk) }}"
                    class="text-indigo-600 hover:underline">Lihat PDF</a>
            </p>
            <p class="text-xs text-gray-500">
                Kosongkan jika tidak ingin mengganti lampiran.
            </p>
        @endif
    </div>

</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit"
        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
        {{ $suratMasuk ? 'Simpan Perubahan' : 'Simpan Surat' }}
    </button>

    <a href="{{ route('surat-masuk.index') }}"
        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
        Batal
    </a>
</div>
