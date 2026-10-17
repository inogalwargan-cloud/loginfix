
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Data Surat Keluar</h2>

            <a href="{{ route('surat-keluar.create') }}"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-700">
                + Tambah Surat
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Surat Keluar</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ number_format(\App\Models\SuratKeluar::count()) }}
                    </p>
                </div>

                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Draft</p>
                    <p class="mt-2 text-2xl font-bold text-gray-600">
                        {{ number_format(\App\Models\SuratKeluar::where('status', 'draft')->count()) }}
                    </p>
                </div>

                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Terkirim</p>
                    <p class="mt-2 text-2xl font-bold text-green-600">
                        {{ number_format(\App\Models\SuratKeluar::where('status', 'terkirim')->count()) }}
                    </p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="border-b border-gray-200 p-5">
                    <form action="{{ route('surat-keluar.index') }}" method="GET"
                        class="flex flex-col gap-3 sm:flex-row">
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari nomor surat, tujuan, atau perihal..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                        <button type="submit"
                            class="rounded-lg bg-gray-800 px-5 py-2 text-sm font-semibold text-white">
                            Cari
                        </button>

                        @if ($search)
                            <a href="{{ route('surat-keluar.index') }}"
                                class="rounded-lg border border-gray-300 px-5 py-2 text-center text-sm">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">No.</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Nomor Agenda</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Nomor Surat</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tanggal</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tujuan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Perihal</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($suratKeluars as $surat)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">
                                        {{ $suratKeluars->firstItem() + $loop->index }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700">
                                        {{ $surat->nomor_agenda }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700">
                                        {{ $surat->nomor_surat ?: '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                        {{ $surat->tanggal_surat->format('d-m-Y') }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        {{ $surat->tujuan }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        {{ $surat->perihal }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm">
                                        @php
                                            $warna = match ($surat->status) {
                                                'draft' => 'bg-gray-100 text-gray-700',
                                                'proses' => 'bg-amber-100 text-amber-700',
                                                'terkirim' => 'bg-green-100 text-green-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $warna }}">
                                            {{ ucfirst($surat->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('surat-keluar.show', $surat) }}"
                                                class="font-medium text-indigo-600 hover:underline">Detail</a>

                                            <a href="{{ route('surat-keluar.edit', $surat) }}"
                                                class="font-medium text-amber-600 hover:underline">Edit</a>

                                            <form action="{{ route('surat-keluar.destroy', $surat) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="font-medium text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center">
                                        <p class="font-medium text-gray-700">Belum ada surat keluar.</p>
                                        <p class="mt-1 text-sm text-gray-500">Klik Tambah Surat untuk mulai mencatat.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 p-5">
                    {{ $suratKeluars->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
