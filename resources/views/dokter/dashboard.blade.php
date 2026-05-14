<x-app-layout>
    <x-slot name="title">Dashboard Dokter</x-slot>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold">Selamat Datang, dr. {{ Auth::user()->name }}!</h1>
        <p>Anda login sebagai Dokter.</p>
    </div>
</x-app-layout>