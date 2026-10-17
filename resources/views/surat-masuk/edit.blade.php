
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Edit Surat Masuk
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                        Periksa kembali data yang kamu masukkan.
                    </div>
                @endif

                <form action="{{ route('surat-masuk.update', $suratMasuk) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('surat-masuk._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
