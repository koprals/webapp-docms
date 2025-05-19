@extends(backpack_view('blank'))

@php
    if (backpack_theme_config('show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => backpack_view('inc.getting_started'),
        ];
    } else {
        $widgets['before_content'][] = [
            'type'        => 'jumbotron',
            'heading'     => trans('backpack::base.welcome'),
            'heading_class' => 'display-3 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
            'content'     => trans('backpack::base.use_sidebar'),
            'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
            'button_link' => backpack_url('logout'),
            'button_text' => trans('backpack::base.logout'),
        ];
    }
@endphp

@section('content')
    <h2>Dashboard</h2>
    <div class="row">
        <!-- Widget Jumlah Klien -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Klien</h5>
                    <h2 class="card-text">{{ $total_klien }}</h2>
                </div>
            </div>
        </div>

        <!-- Widget Jumlah Permohonan -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Permohonan</h5>
                    <h2 class="card-text">{{ $total_permohonan }}</h2>
                </div>
            </div>
        </div>

        <!-- Widget Permohonan Baru -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Permohonan Baru</h5>
                    <h2 class="card-text">{{ $permohonan_baru }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Dokumen Pending -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">5 Dokumen Tertua Belum Di-Approve</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Dokumen</th>
                            <th>Jenis Dokumen</th>
                            <th>Permohonan</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumen_pending as $dokumen)
                        <tr>
                            <td>{{ $dokumen->id_dokumen }}</td>
                            <td>{{ $dokumen->jenisDokumen->nama_jenis }}</td>
                            <td>
                                <a href="{{ backpack_url('permohonan/'.$dokumen->permohonan->id_permohonan.'/show') }}">
                                    #{{ $dokumen->permohonan->id_permohonan }}
                                </a>
                            </td>
                            <td>{{ $dokumen->created_on->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ backpack_url('dokumen/'.$dokumen->id_dokumen.'/edit') }}" class="btn btn-sm btn-primary">
                                    <i class="la la-edit"></i> Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada dokumen pending</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
