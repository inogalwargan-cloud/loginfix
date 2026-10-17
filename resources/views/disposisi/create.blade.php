
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Berikan Disposisi
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">

                <div class="mb-6 rounded-lg bg-gray-50 p-4">
                    <p class="text-sm text-gray-500">Nomor Surat</p>
                    <p class="font-semibold text-gray-900">
                        {{ $suratMasuk->nomor_surat }}
                    </p>

                    <p class="mt-3 text-sm text-gray-500">Pengirim</p>
                    <p class="font-medium text-gray-900">
                        {{ $suratMasuk->pengirim }}
                    </p>

                    <p class="mt-3 text-sm text-gray-500">Perihal</p>
                    <p class="font-medium text-gray-900">
                        {{ $suratMasuk->perihal }}
                    </p>
                </div>

                <form method="POST"
                    action="{{ route('disposisi.store', $suratMasuk) }}">
                    @csrf

                    <div class="mb-5">
                        <label for="penerima_id"
                            class="block text-sm font-medium text-gray-700">
                            Tugaskan kepada staf *
                        </label>

                        <select id="penerima_id" name="penerima_id" required
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">-- Pilih staf --</option>
                            @foreach ($staf as $pegawai)
                                <option value="{{ $pegawai->id }}"
                                    @selected(old('penerima_id') == $pegawai->id)>
                                    {{ $pegawai->name }} ({{ $pegawai->email }})
                                </option>
                            @endforeach
                        </select>

                        @error('penerima_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="instruksi"
                            class="block text-sm font-medium text-gray-700">
                            Instruksi disposisi *
                        </label>

                        <textarea id="instruksi" name="instruksi"
                            rows="5" required maxlength="5000"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                            placeholder="Contoh: Mohon diperiksa dan siapkan tindak lanjut.">{{ old('instruksi') }}</textarea>

                        @error('instruksi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="batas_waktu"
                            class="block text-sm font-medium text-gray-700">
                            Batas waktu
                        </label>

                        <input type="date" id="batas_waktu"
                            name="batas_waktu" min="{{ now()->toDateString() }}"
                            value="{{ old('batas_waktu') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm">

                        @error('batas_waktu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                            Kirim Disposisi
                        </button>

                        <a href="{{ route('surat-masuk.show', $suratMasuk) }}"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
