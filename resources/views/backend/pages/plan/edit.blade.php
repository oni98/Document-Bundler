@extends('backend.layouts.app')

@section('template_title')
   Plan
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
                    <h1 class="m-0">{{ $plan->package->name }} -  {{ $plan->name }} - Edit</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item "><a href="{{ route('plan.show',[$plan->package->id]) }}">{{ $plan->package->name }}</a></li>
                        <li class="breadcrumb-item ">{{ $plan->name }}</li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <form action="{{ route('plan.update',[$plan->id]) }}" method="post">
                                @csrf
                                @method("PUT")
                                <div class="form-group">
                                    <label for="name">Plan Name</label>
                                    <input type="text" name="name" value="{{ $plan->name }}" id="" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="UPDATE" class="btn btn-success">
                                </div>
                            </form>
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
