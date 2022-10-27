@extends('backend.layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Bundle
@endsection

@push('custom-css')
    <style>
        .social-links {}

        .social-links ul {
            padding: 0;
            margin: 0;
        }

        .social-links ul li {
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bundle</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
                        <li class="breadcrumb-item "><a href="{{ route('bundle.index') }}">Bundle</a></li>
                        <li class="breadcrumb-item active">Generated BUNDLE</li>
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
                            @if (Session::has('message'))
                                <div class="alert alert-success">
                                    <h4>{{ Session::get('message') }}</h4>
                                </div>
                            @endif
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Generated Bundle Name</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($bundle->generated as $b)
                                        <tr>
                                            <td>
                                                {{ $b->filename }}
                                            </td>
                                            <td>

                                                <a href="{{ route('pdf', $b->id) }}" class="btn btn-outline-primary"><i
                                                        class="fa fa-download"></i> DOWNLOAD</a>
                                                <div class="social-links">
                                                    {!! Share::page(route('pdf', $b->id))->facebook()->twitter()->linkedin()->whatsapp() !!}

                                                </div>
                                                <form action="{{ route('bundle.generated.destroy', [$b->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"><i
                                                            class="fa fa-trash"></i> DELETE</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
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
    <script src="{{ asset('js/share.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.social-links ul li a').addClass('btn');
            $('.social-links ul li a').addClass('btn-outline-primary');
        });
    </script>
@endpush
