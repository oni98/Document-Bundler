@extends('backend.layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Settings
@endsection

@push('custom-css')
    @livewireStyles
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Plan Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item ">Settings</li>
                        <li class="breadcrumb-item ">Plan</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                @foreach ($package as $pack)
                    <section class="col-md-4 connectedSortable">

                        <div class="card">
                            <div class="card-header">
                                {{ data_get($pack, 'name', '') }}
                            </div>
                            <div class="card-body">
                                <livewire:plans :package="$pack" />
                            </div>
                        </div>
                    </section>
                @endforeach
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->
@endsection
@push('custom-script')
    @livewireScripts
@endpush
