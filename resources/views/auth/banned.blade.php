@extends('layouts.app')

@section('title', 'Akun Anda Dibanned')
@section('page_title', 'Akun Terblokir')
@section('page_subtitle', 'Silakan hubungi admin atau kirim banding')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-8">
        <div class="text-center">
            <div class="text-6xl mb-4">🚫</div>
            <h1 class="text-2xl font-bold text-red-600">Akun Anda Dibanned</h1>
            <p class="text-slate-600 mt-2">{{ $reason }}</p>
            <p class="text-slate-600">Reputasi Anda saat ini: <strong class="text-red-600">{{ $reputation }}</strong> poin</p>
            <p class="text-slate-500 text-sm mt-1">Minimal reputasi <strong>30 poin</strong> untuk dapat menggunakan sistem.</p>
        </div>

        @if(session('success'))
        <div class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mt-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            ❌ {{ session('error') }}
        </div>
        @endif

        @if($hasAppeal)
            <div class="mt-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-sm text-center">
                📨 Anda sudah mengirim banding. Mohon tunggu respons admin.
            </div>
        @else
            <form method="POST" action="{{ route('banned.appeal') }}" class="mt-6 text-left">
                @csrf
                <label class="text-sm font-semibold text-slate-700">Kirim Banding</label>
                <textarea name="message" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror" placeholder="Jelaskan alasan Anda merasa akun ini seharusnya diaktifkan kembali..." required></textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <button type="submit" class="mt-3 w-full px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition shadow-sm">
                    Kirim Banding
                </button>
            </form>
        @endif

        <p class="text-xs text-slate-400 text-center mt-4">Admin akan memproses banding dalam 1×24 jam</p>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-slate-500 hover:text-red-600 transition font-medium">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection