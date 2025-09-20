@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-end mb-4 mt-4">
            <h1 class="mb-0">
                Metode Pengendalian
            </h1>
            <div class="text-muted">
                Total: {{ $pengendalians->total() }} metode
            </div>
        </div>

        <!-- Filter dan Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('pengendalian.index') }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="search" class="form-label">Pencarian</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Cari berdasarkan jenis atau deskripsi...">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="jenis" class="form-label">Filter Jenis</label>
                            <select class="form-select" id="jenis" name="jenis">
                                <option value="">Semua Jenis</option>
                                <option value="Biologis" {{ request('jenis') === 'Biologis' ? 'selected' : '' }}>Biologis
                                </option>
                                <option value="Kimiawi" {{ request('jenis') === 'Kimiawi' ? 'selected' : '' }}>Kimiawi
                                </option>
                                <option value="Fisik" {{ request('jenis') === 'Fisik' ? 'selected' : '' }}>Fisik</option>
                                <option value="Budidaya" {{ request('jenis') === 'Budidaya' ? 'selected' : '' }}>Budidaya
                                </option>
                                <option value="Terpadu" {{ request('jenis') === 'Terpadu' ? 'selected' : '' }}>Terpadu
                                </option>
                            </select>
                        </div>
                        <div class="col mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results -->
        @if ($pengendalians->count() > 0)
            <div class="row">
                @foreach ($pengendalians as $pengendalian)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ $pengendalian->jenis }}</span>
                                    @if ($pengendalian->organisme->count() > 0)
                                        <small class="text-muted">
                                            {{ $pengendalian->organisme->count() }} organisme
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    {{ Str::limit($pengendalian->deskripsi, 120) }}
                                </p>

                                @if ($pengendalian->insektisida->count() > 0)
                                    <div class="mb-2">
                                        <small class="text-success">
                                            <i class="fas fa-spray-can me-1"></i>
                                            {{ $pengendalian->insektisida->count() }} insektisida tersedia
                                        </small>
                                    </div>
                                @endif

                                @if ($pengendalian->organisme->count() > 0)
                                    <div class="mb-3">
                                        <small class="text-success d-block mb-2 ">
                                            <i class="fas fa-bug me-1"></i>Efektif untuk:
                                        </small>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($pengendalian->organisme->take(3) as $organisme)
                                                <span class="badge bg-light text-dark">
                                                    {{ $organisme->nama_opt }}
                                                </span>
                                            @endforeach
                                            @if ($pengendalian->organisme->count() > 3)
                                                <span class="badge bg-secondary">
                                                    +{{ $pengendalian->organisme->count() - 3 }} lainnya
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-white">
                                <a href="{{ route('pengendalian.show', $pengendalian->id) }}"
                                    class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $pengendalians->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-search fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted">Tidak ada metode pengendalian ditemukan</h5>
                <p class="text-muted">
                    @if (request('search') || request('jenis'))
                        Coba ubah kriteria pencarian atau filter Anda.
                    @else
                        Belum ada data metode pengendalian yang tersedia.
                    @endif
                </p>
                @if (request('search') || request('jenis'))
                    <a href="{{ route('pengendalian.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Lihat Semua
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Custom CSS -->
    <style>
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .badge {
            font-size: 0.75em;
        }

        .card-text {
            font-size: 0.9rem;
            line-height: 1.5;
        }
    </style>
@endsection
