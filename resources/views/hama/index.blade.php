@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="detection-header">
                    <div class="row align-items-center">
                        <div class="col-md-6 mt-4 mb-4">
                            <h2 class="mb-2 text-success">Daftar Hama dan Penyakit</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <!-- Symptoms Selection -->
            <div class="col-md-12">
                <div class="card symptoms-section p-3">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                    Gambar</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Jenis</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
