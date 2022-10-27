@extends('backend.layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Settings
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
                    <h1 class="m-0">Payment Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item ">Settings</li>
                        <li class="breadcrumb-item ">Payment</li>
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
                <section class="col-md-6 connectedSortable">

                    <div class="card">
                        <div class="card-header">
                            Paypal Settings
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('setting.store.payment') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="paypal">
                                <div class="form-group">
                                    <label for="">PAYPAL CLIENT ID</label>
                                    <input type="text" name="PAYPAL_CLIENT_ID"
                                        value="{{ data_get($PAYPAL_CLIENT_ID, 'value', '') }}" class="form-control"
                                        id="">
                                </div>
                                <div class="form-group">
                                    <label for="">PAYPAL CLIENT SECRET</label>
                                    <input type="text" name="PAYPAL_CLIENT_SECRET"
                                        value="{{ data_get($PAYPAL_CLIENT_SECRET, 'value', '') }}" class="form-control"
                                        id="">
                                </div>
                                <input type="submit" value="UPDATE" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </section>
                <!-- /.Left col -->
                <!-- Right Col -->
                <section class="col-md-6 connectedSortable">

                    <div class="card">
                        <div class="card-header">
                            Stripe Settings
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('setting.store.payment') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="stripe">
                                <div class="form-group">
                                    <label for="">STRIPE PUBLISHABLE KEY</label>
                                    <input type="text" name="STRIPE_PUBLISHABLE_KEY"
                                        value="{{ data_get($STRIPE_PUBLISHABLE_KEY, 'value', '') }}" class="form-control"
                                        id="">
                                </div>
                                <div class="form-group">
                                    <label for="">STRIPE SECRET KEY</label>
                                    <input type="text" name="STRIPE_SECRET_KEY"
                                        value="{{ data_get($STRIPE_SECRET_KEY, 'value', '') }}" class="form-control"
                                        id="">
                                </div>
                                <input type="submit" value="UPDATE" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </section>
                <!-- Right Col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->
@endsection
