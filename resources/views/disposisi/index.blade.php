
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Disposisi Surat
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Surat</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Instruksi</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Pemberi</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Penerima</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Batas Waktu</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tindak Lanjut</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($disposisis as $disposisi)
                                <tr class="align-top hover:bg-gray-50">
                                    <td class="px-5 py-4 text-sm">
                                        <a href="{{ route('surat-masuk.show', $disposisi->suratMasuk) }}"
                                            class="font-medium text-indigo-600 hover:underline">
                                            {{ $disposisi->suratMasuk->nomor_surat }}
                                        </a>
                                        <p class="mt-1 text-gray-500">
                                            {{ $disposisi->suratMasuk->perihal }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        {{ $disposisi->instruksi }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        {{ $disposisi->pemberi->name }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-gray-700">
                                        {{ $disposisi->penerima->name }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                        {{ $disposisi->batas_waktu?->format('d-m-Y') ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm">
                                        @php
                                            $warna = match ($disposisi->status) {
                                                'baru' => 'bg-blue-100 text-blue-700',
                                                'diproses' => 'bg-amber-100 text-amber-700',
                                                'selesai' => 'bg-green-100 text-green-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $warna }}">
                                            {{ ucfirst($disposisi->status) }}
                                        </span>

                                        @if ($disposisi->catatan_hasil)
                                            <p class="mt-2 max-w-xs whitespace-pre-line text-gray-500">
                                                {{ $disposisi->catatan_hasil }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="min-w-64 px-5 py-4">
                                        @if (
                                            auth()->user()->isRole('admin', 'pimpinan')
                                            || (
                                                auth()->user()->isRole('staf')
                                                && $disposisi->penerima_id === auth()->id()
                                                && $disposisi->status !== 'selesai'
                                            )
                                        )
                                            <form method="POST"
                                                action="{{ route('disposisi.update', $disposisi) }}"
                                                class="space-y-3">
                                                @csrf
                                                @method('PATCH')

                                                <select name="status" required
                                                    class="w-full rounded-lg border-gray-300 text-sm">
                                                    @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $value => $label)
                                                        @if (
                                                            !auth()->user()->isRole('staf')
                                                            || $value !== 'baru'
                                                        )
                                                            <option value="{{ $value }}"
                                                                @selected(old('status', $disposisi->status) === $value)>
                                                                {{ $label }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                <textarea name="catatan_hasil" rows="2"
                                                    maxlength="5000"
                                                    placeholder="Catatan tindak lanjut..."
                                                    class="w-full rounded-lg border-gray-300 text-sm">{{ old('catatan_hasil', $disposisi->catatan_hasil) }}</textarea>

                                                <button type="submit"
                                                    class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white">
                                                    Simpan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-400">Tidak ada tindakan.</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                        Belum ada disposisi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t p-5">
                    {{ $disposisis->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
