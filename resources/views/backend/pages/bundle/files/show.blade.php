@extends('backend.layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Bundle
@endsection

@push('custom-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item "><a href="{{ route('bundle.index') }}">Bundle</a></li>
                            <li class="breadcrumb-item "><a
                                    href="{{ route('bundle.show_single', [$file->bundle->slug, $file->bundle->id]) }}">{{ $file->bundle->name }}</a>
                            </li>
                            <li class="breadcrumb-item "><a
                                    href="{{ route('public.bundle.section.edit', [$file->bundle->id, $file->section->id]) }}">{{ $file->section->name }}</a>
                            </li>
                            <li class="breadcrumb-item">{{ $file->mime_types }}</li>
                            <li class="breadcrumb-item active">Edit</li>
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
                <section class="col-12 pt-4 pb-2">
                    <a href="{{ asset('pdf/' . $file->filename) }}" class="btn btn-primary"><i class="fa fa-download"></i>
                        DOWNLOAD & PREVIEW</a>
                </section>
                <!-- /.Right Col -->

                <!-- Left col -->
                <section class="col-12 connectedSortable">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('public.bundle.files.rename') }}" method="post">
                                @csrf
                                <input type="hidden" name="file_id" value="{{ $file_id }}" />
                                <div class="form-group">
                                    <label for="">File Name</label>
                                    <input type="text" value="{{ $file->name }}" name="name" class="form-control"
                                        id="">
                                </div>
                                <input type="submit" value="RENAME" class="btn btn-success">
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('public.bundle.files.update') }}" enctype="multipart/form-data"
                                method="post" id="image-upload" class="dropzone">
                                @csrf
                                <input type="hidden" name="file_id" value="{{ $file_id }}" />
                                <input type="hidden" name="bundle_id" value="{{ $bundle_id }}" />
                                <input type="hidden" name="section_id" value="{{ $section_id }}" />
                                <div class="text-center">
                                    <h3>Upload .jpeg,.jpg,.png,.gif,.doc,.docx,.pdf By Click On Box</h3>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <!-- /.Left col -->
                <!-- Right Col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->
@endsection

@push('custom-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>

    <script type="text/javascript">
        Dropzone.options.imageUpload = {
            maxFilesize: 50,
            uploadMultiple: false,
            queueLimit: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.doc,.docx,.pdf",
            init: function() {
                var home = "{{ route('section.show', [$file->section_id]) }}";
                //now we will submit the form when the button is clicked
                this.on("success", function(files, response) {
                    location.href = home; // this will redirect you when the file is added to dropzone
                    //location.reload();
                });
            }
        };
    </script>
@endpush
