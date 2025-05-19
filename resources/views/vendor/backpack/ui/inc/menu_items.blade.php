{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}">
    <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a>
</li>

@if (backpack_user()->hasRole('admin'))
    <x-backpack::menu-item title="Jenis Dokumen" icon="la la-th-list" :link="backpack_url('jenis-dokumen')" />
    <x-backpack::menu-item title="Jenis Permohonan" icon="la la-th-list" :link="backpack_url('jenis-permohonan')" />
    <x-backpack::menu-item title="Klien" icon="la la-users" :link="backpack_url('klien')" />
@endif

<x-backpack::menu-item title="Permohonan" icon="la la-file" :link="backpack_url('permohonan')" />
