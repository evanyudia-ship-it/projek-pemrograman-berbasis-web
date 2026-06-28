@extends('layouts.app')

@section('title', 'Edit Ruang - Admin')
@section('page_title', 'Edit Ruang')
@section('page_subtitle', "{{ $room->nama }} ({$room->kode})")

@section('content')

<div class="max-w-4xl mx-auto font-sora">

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">

        <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Nama Ruang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $room->nama) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('nama') border-red-500 @enderror"
                           placeholder="Contoh: Ruang Seminar A - Lt. 3" required>
                    @error('nama')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Kode Ruang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $room->kode) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('kode') border-red-500 @enderror"
                           placeholder="Contoh: RSA-301" required>
                    @error('kode')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('status') border-red-500 @enderror">
                        <option value="Tersedia" {{ old('status', $room->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Maintenance" {{ old('status', $room->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gedung --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Gedung <span class="text-red-500">*</span></label>
                    <input type="text" name="gedung" value="{{ old('gedung', $room->gedung) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('gedung') border-red-500 @enderror"
                           placeholder="Contoh: Gedung A" required>
                    @error('gedung')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lantai --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Lantai <span class="text-red-500">*</span></label>
                    <input type="number" name="lantai" value="{{ old('lantai', $room->lantai) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('lantai') border-red-500 @enderror"
                           min="1" required>
                    @error('lantai')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Kapasitas (orang) <span class="text-red-500">*</span></label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', $room->kapasitas) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('kapasitas') border-red-500 @enderror"
                           min="1" placeholder="Contoh: 40" required>
                    @error('kapasitas')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jam Buka --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Jam Buka <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_buka" value="{{ old('jam_buka', $room->jam_buka?->format('H:i')) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('jam_buka') border-red-500 @enderror"
                           required>
                    @error('jam_buka')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jam Tutup --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Jam Tutup <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $room->jam_tutup?->format('H:i')) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('jam_tutup') border-red-500 @enderror"
                           required>
                    @error('jam_tutup')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Max Durasi --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Maks. Durasi (Jam) <span class="text-red-500">*</span></label>
                    <input type="number" name="max_durasi_jam" value="{{ old('max_durasi_jam', $room->max_durasi_jam) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('max_durasi_jam') border-red-500 @enderror"
                           min="1" max="24" required>
                    @error('max_durasi_jam')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Faculty --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700">Fakultas</label>
                    <select name="faculty_id" class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('faculty_id') border-red-500 @enderror">
                        <option value="">Tanpa Fakultas</option>
                        @foreach($faculties ?? [] as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id', $room->faculty_id) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('faculty_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $room->alamat) }}"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('alamat') border-red-500 @enderror"
                           placeholder="Alamat lengkap ruangan">
                    @error('alamat')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fasilitas --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Fasilitas</label>
                    <div class="mt-1.5 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @php
                            $selectedFacilities = old('facilities', $room->facilities->pluck('id')->toArray());
                        @endphp
                        @foreach($allFacilities ?? [] as $facility)
                        <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                   {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}>
                            <span class="text-sm text-slate-700">
                                @if($facility->icon)<span class="mr-1">{{ $facility->icon }}</span>@endif
                                {{ $facility->nama }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('facilities')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Foto Ruang</label>
                    @if($room->foto)
                    <div class="mt-2 mb-3">
                        <img src="{{ asset('storage/' . $room->foto) }}"
                             alt="{{ $room->nama }}"
                             class="w-48 h-32 object-cover rounded-xl border border-slate-200">
                        <p class="text-xs text-slate-400 mt-1">Foto saat ini</p>
                    </div>
                    @endif
                    <input type="file" name="foto" accept="image/*"
                           class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('foto') border-red-500 @enderror">
                    <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah foto. Maksimal 2MB. Format: JPG, PNG</p>
                    @error('foto')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                              class="mt-1.5 w-full rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi singkat ruangan...">{{ old('deskripsi', $room->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol --}}
            <div class="mt-6 flex flex-col md:flex-row gap-3 md:justify-end border-t border-slate-100 pt-6">
                <a href="{{ route('admin.rooms.index') }}"
                   class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-center transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-700 text-white font-semibold transition">
                    💾 Update Ruang
                </button>
            </div>

        </form>
    </div>

</div>

@endsection
