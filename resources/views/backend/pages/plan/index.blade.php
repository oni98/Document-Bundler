@extends('backend.layouts.app')

@section('template_title')
   Package
@endsection

@push('custom-css')
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $package->name }} - Plan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('package.index') }}">Package</a></li>
                        <li class="breadcrumb-item "> {{ $package->name }} </li>
                        <li class="breadcrumb-item "> Plan</li>
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
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <div class="card">
                        <div class="card-body">
                             @if (count($errors) > 0)
                                <div class = "alert alert-danger">
                                    <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('plan.storeById',[$package->id]) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Plan Name</label>
                                    <input type="text" name="name" id="" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-stripped">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Name</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($plan) > 0)
                                    @php
                                        $i =1;
                                    @endphp
                                    @foreach($plan as $p)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $p->name }}</td>
                                            <td>
                                                <a href="{{ route('plan.edit',[$p->id]) }}" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i> RENAME
                                                </a>
                                                <a href="{{ route('plan.destroy',[$p->id]) }}" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3">NO DATA FOUND</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->
@endsection

@push('custom-script')
@endpush
