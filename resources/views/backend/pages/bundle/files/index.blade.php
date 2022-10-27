@extends('backend.layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Bundle
@endsection

@push('custom-css')
@endpush

@section('content')
    <div style="display: none">
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
                            <li class="breadcrumb-item "><a
                                    href="{{ route('bundle.show_single', [$section->bundle->slug, $section->bundle->id]) }}">{{ $section->bundle->name }}</a>
                            </li>
                            <li class="breadcrumb-item ">{{ $section->name }}</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-center py-4"><b>{{ $section->name }}</b></h2>
                        </div>
                    </div>
                    <div class="row align-items-center pb-2">
                        <div class="col-lg-6">
                            <a href="{{ route('public.bundle.files.create', [$section->bundle_id, $section->id]) }}"
                                class='btn btn-primary'><i class="fa fa-upload mr-2"></i> Add FIle</a>
                        </div>

                        <div class="col-lg-6">
                            <ol class="breadcrumb float-sm-right m-0 p-0 bg-transparent">
                                <li class="breadcrumb-item text-uppercase text-bold"><a
                                        href="{{ route('bundle.index') }}">Bundle</a></li>
                                <li class="breadcrumb-item text-uppercase text-bold"><a
                                        href="{{ route('bundle.show_single', [$section->bundle->slug, $section->bundle->id]) }}">{{ $section->bundle->name }}</a>
                                </li>
                                <li class="breadcrumb-item text-bold">{{ $section->name }}</a></li>
                            </ol>
                        </div>
                    </div>

                    <div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Page</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody class="sort_files">
                                @foreach ($section->files as $f)
                                    @php
                                        $filename = explode('.', $f->filename);
                                    @endphp
                                    <tr data-id="{{ $f->id }}" class="clickable-row"
                                        data-href="{{ route('public.bundle.files.show', [$section->bundle_id, $section->id, $f->id]) }}">

                                        <td class="py-1 pl-3 align-middle"><span class="handle"></span>{{ $f->name }}
                                        </td>
                                        <td class="py-1 pl-3 align-middle">{{ $f->totalPage }}</td>
                                        <td class="py-1 pl-3 align-middle">
                                            <a href="{{ route('public.bundle.files.show', [$section->bundle_id, $section->id, $f->id]) }}"
                                                data-toggle="tooltip" data-placement="top" title="Edit"
                                                class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></a>
                                            <a href="{{ route('public.bundle.files.delete', [$f->id]) }}"
                                                data-toggle="tooltip" data-placement="top" title="Delete"
                                                class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    <script>
        $(document).ready(function() {
            $('tbody').sortable();

            function updateToDatabase(idString) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                $.ajax({
                    url: '{{ url('/bundle/files/update-order') }}',
                    method: 'POST',
                    data: {
                        ids: idString
                    },
                    success: function() {
                        //  alert('Successfully updated')
                        //do whatever after success
                    }
                })
            }

            var target = $('.sort_files');
            target.sortable({
                update: function(e, ui) {
                    var sortData = target.sortable('toArray', {
                        attribute: 'data-id'
                    })
                    updateToDatabase(sortData.join(','))
                }
            })

        })
    </script>
@endpush
