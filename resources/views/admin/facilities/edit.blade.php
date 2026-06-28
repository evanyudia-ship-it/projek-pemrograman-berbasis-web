@extends('layouts.app')

@section('title', 'Edit Fasilitas - Admin')
@section('page_title', 'Edit Fasilitas')
@section('page_subtitle', $facility->nama)

@section('content')

<div class="max-w-3xl mx-auto font-sora">

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">

        <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- Preview Icon --}}
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-4xl shadow-sm">
                        {{ $facility->icon ?? '📦' }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $facility->nama }}</p>
                        <p class="text-xs text-slate-400">Sedang diedit</p>
                    </div>
                </div>

                {{-- Nama --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Nama Fasilitas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $facility->nama) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('nama') border-red-500 @enderror"
                           placeholder="Contoh: Proyektor HD, AC, WiFi" required>
                    @error('nama')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Icon --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Icon (Emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $facility->icon) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('icon') border-red-500 @enderror"
                           placeholder="Contoh: 📽️, ❄️, 📶" maxlength="10">
                    <p class="text-xs text-slate-400 mt-1">Gunakan emoji untuk ikon fasilitas (maksimal 10 karakter)</p>
                    @error('icon')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $facility->kategori) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('kategori') border-red-500 @enderror"
                           placeholder="Contoh: Elektronik, Perabotan, Jaringan">
                    <p class="text-xs text-slate-400 mt-1">Kategori untuk mengelompokkan fasilitas</p>
                    @error('kategori')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                              class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi fasilitas...">{{ old('deskripsi', $facility->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol --}}
            <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end border-t border-slate-100 pt-6">
                <a href="{{ route('admin.facilities.index') }}"
                   class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-center transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-700 text-white font-semibold transition">
                    💾 Update Fasilitas
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
