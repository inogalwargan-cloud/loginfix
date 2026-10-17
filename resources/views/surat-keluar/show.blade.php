
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Detail Surat Keluar</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">

                <div class="flex flex-col justify-between gap-4 border-b pb-5 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $suratKeluar->perihal }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Agenda: {{ $suratKeluar->nomor_agenda }}
                        </p>
                    </div>

                    <a href="{{ route('surat-keluar.edit', $suratKeluar) }}"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-center text-sm font-semibold text-white">
                        Edit Surat
                    </a>
                </div>

                <dl class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-gray-500">Nomor Surat</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $suratKeluar->nomor_surat ?: '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Tujuan / Penerima</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $suratKeluar->tujuan }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Tanggal Surat</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $suratKeluar->tanggal_surat->format('d-m-Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Sifat</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ ucfirst($suratKeluar->sifat) }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ ucfirst($suratKeluar->status) }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm text-gray-500">Isi Surat</dt>
                        <dd class="mt-1 whitespace-pre-line text-gray-900">{{ $suratKeluar->isi_surat ?: '-' }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm text-gray-500">Lampiran</dt>
                        <dd class="mt-2">
                            @if ($suratKeluar->file_path)
                                <a href="{{ route('surat-keluar.lampiran', $suratKeluar) }}"
                                    class="font-medium text-indigo-600 hover:underline">
                                    Unduh lampiran PDF
                                </a>
                            @else
                                <span class="text-gray-500">Tidak ada lampiran.</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <div class="mt-8 border-t pt-5">
                    <a href="{{ route('surat-keluar.index') }}"
                        class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        &larr; Kembali ke daftar surat
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
