@extends('layouts.template')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Ditugaskan</p>
                                <h4 class="card-title">{{ $belumDikumpulkan }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Dikumpulkan</p>
                                <h4 class="card-title">{{ $sudahDikumpulkan }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Tugas</p>
                                <h4 class="card-title">{{ $totalTugas }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="far fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Kelas</p>
                                <h4 class="card-title">{{ $totalKelas }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Menampilkan daftar kelas yang diikuti mahasiswa -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Kelas yang Diikuti</h3>
        </div>
        <div class="card-body">
            @if ($totalKelas > 0)
                <div class="row">
                    @foreach ($kelas as $kelass)
                        <div class="col-md-3 mb-2">
                            <!-- Button mati dengan warna kelas -->
                            <button class="btn btn-secondary w-100 disabled">{{ $kelass->kelas->nama_kelas }}</button>
                        </div>
                    @endforeach
                </div>
            @else
                <p>Anda belum terdaftar di kelas manapun.</p>
            @endif
        </div>
    </div>
@endsection
