@extends('backend.layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Bundle
@endsection

@push('custom-css')
@endpush
@php
$enrolled_package = auth()
    ->user()
    ->load('enrolledPackage')->enrolledPackage;
@endphp
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
                            <li class="breadcrumb-item "><a href="{{ route('bundle.index') }}">Bundle</a></li>
                            <li class="breadcrumb-item active">{{ $bundle->name }} List</li>
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
                        <div class="col-sm-12 ">
                            <div class="row align-items-center">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4">
                                    <h2 class="text-center py-4 m-0"><b>{{ $bundle->name }}</b></h2>
                                </div>
                                <div class="col-lg-4 text-right">
                                    <button type="button" class="btn btn-lg btn-warning" id="bundle-tour-button">
                                        <i class="fa fa-question"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <span class="d-inline-block" data-toggle="tooltip" data-placement="top"
                                        title="Add Section">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#sectioncreatemodal" id="bundle-tour-1">
                                            <i class="fa fa-folder-open-o"></i>
                                        </button>
                                    </span>
                                    <div class="modal fade" id="sectioncreatemodal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Create Section</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('section.store') }}" method="post">
                                                    <div class="modal-body">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        @csrf
                                                        <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                                                        <input type="text" placeholder="Section Name"
                                                            class="form-control" name="name" required>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-primary" value="Create" />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <span class="d-inline-block" data-toggle="tooltip" data-placement="top"
                                        title="Upload Document">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            id="bundle-tour-2" data-target="#exampleModal">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                    </span>
                                    @if ($enrolled_package->package_id == 1)
                                        @if ($bundle->totalPages() < 61 && $bundle->totalPages() > 0)
                                            <a href="{{ route('public.bundle.generate', [$bundle->id]) }}"
                                                data-toggle="tooltip" data-placement="top" title="Generate Bundle"
                                                id="bundle-tour-3" class="btn btn-info"><i class="fa fa-file-text"></i></a>
                                        @else
                                            <a href="#" class="btn btn-info" data-toggle="tooltip"
                                                data-placement="top" title="Generate Bundle" id="bundle-tour-3"><i
                                                    class="fa fa-file-text"></i></a>
                                        @endif
                                    @else
                                        <a href="{{ route('public.bundle.generate', [$bundle->id]) }}" data-toggle="tooltip"
                                            data-placement="top" title="Generate Bundle" id="bundle-tour-3"
                                            class="btn btn-info"><i class="fa fa-file-text"></i></a>
                                    @endif
                                    @if ($enrolled_package->package_id == 1)
                                        @if ($bundle->totalPages() < 61 && $bundle->totalPages() > 0)
                                            <a href="{{ route('public.bundle.generated_bundle', [$bundle->id]) }}"
                                                data-toggle="tooltip" data-placement="top" title="View Generated Bundle"
                                                id="bundle-tour-4" class="btn btn-info"><i
                                                    class="fa fa-file-pdf-o"></i></a>
                                        @else
                                            <a href="#" class="btn btn-info" data-toggle="tooltip"
                                                data-placement="top" title="View Generated Bundle" id="bundle-tour-4"><i
                                                    class="fa fa-file-pdf-o"></i></a>
                                        @endif
                                    @else
                                        <a href="{{ route('public.bundle.generated_bundle', [$bundle->id]) }}"
                                            data-toggle="tooltip" data-placement="top" title="View Generated Bundle"
                                            id="bundle-tour-4" class="btn btn-info"><i class="fa fa-file-pdf-o"></i></a>
                                    @endif
                                </div>

                                <div class="col-lg-6">
                                    <ol class="breadcrumb float-sm-right m-0 p-0 bg-transparent">
                                        <li class="breadcrumb-item text-uppercase text-bold "><a
                                                href="{{ route('bundle.index') }}">Bundle</a></li>
                                        <li class="breadcrumb-item text-bold">{{ $bundle->name }} List</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Section Name</th>
                                        <th>Total Page</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="sort_section">
                                    @foreach ($bundle->section as $s)
                                        @if ($s->isHiddenInList == 1)
                                        @else
                                            <tr data-id="{{ $s->id }}" class="clickable-row"
                                                data-href="{{ route('section.show', $s->id) }}">
                                                <td class="py-1 pl-3 align-middle">
                                                    {{ $s->name }}
                                                </td>
                                                <td class="py-1 pl-3 align-middle">
                                                    {{ $s->files->sum('totalPage') }}
                                                </td>
                                                <td class="py-1 pl-3 align-middle text-right">
                                                    <a href="{{ route('section.show', $s->id) }}" data-toggle="tooltip"
                                                        data-placement="top" title="View"
                                                        class="btn btn-primary d-inline-block"><i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('public.bundle.section.edit', [$bundle->id, $s->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Rename"
                                                        class="btn btn-primary d-inline-block"><i
                                                            class="fa fa-pencil-square-o"></i>
                                                    </a>
                                                    <a href="{{ route('public.bundle.section.destroy', [$s->id]) }}"
                                                        data-toggle="tooltip" data-placement="top" title="Delete"
                                                        class="btn btn-danger d-inline-block"><i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Document</h5>
                    <button type="button" class="close" data-dismiss="modal" onClick="window.location.reload();"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('public.bundle.files.store') }}" enctype="multipart/form-data" method="post"
                        id="image-upload" class="dropzone">
                        @csrf
                        <label>SECTION</label>
                        <select class="form-control" id="sectionId" name="section_id" required>
                            @foreach ($bundle->section as $item)
                                @if ($item->isHiddenInList == 1)
                                @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endif
                            @endforeach
                        </select><br>
                        <input type="hidden" name="bundle_id" value="{{ $bundle->id }}" />
                        <div class="text-center">
                            <p>Upload .jpeg,.jpg,.png,.gif,.doc,.docx,.pdf By Click On Box</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onClick="window.location.reload();"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
    <script type="text/javascript">
        $("#sectionId").on('change', function() {
            if (!$(this).val() == "") {
                $('#image-upload').addClass('dropzone');
            } else {
                $('#image-upload').removeClass('dropzone');
            }
        });
        Dropzone.options.imageUpload = {
            maxFilesize: 50,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.doc,.docx,.pdf",
            init: function() {
                //now we will submit the form when the button is clicked
                this.on("error", function(file, responseText) {
                    $('.dz-error-message').text(responseText.message);
                });
                this.on("success", function(files, response) {
                    // location.href = home; // this will redirect you when the file is added to dropzone
                    location.reload();
                });
            }

        };
    </script>
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
                    url: '{{ url('/bundle/section/update-order') }}',
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

            var target = $('.sort_section');
            target.sortable({
                update: function(e, ui) {
                    var sortData = target.sortable('toArray', {
                        attribute: 'data-id'
                    })
                    updateToDatabase(sortData.join(','))
                }
            })

        })

        // tour setup
        var tour = {
                id: 'bundle-tour',
                steps: [{
                        target: 'bundle-tour-1',
                        title: 'Click to Create New Section',
                        content: 'Click Here to create new Section',
                        placement: 'top',
                        arrowOffset: 5
                    },
                    {
                        target: 'bundle-tour-2',
                        title: 'Click to Upload Document',
                        content: 'Click to Upload Document',
                        placement: 'top',
                        arrowOffset: 5
                    },
                    {
                        target: 'bundle-tour-3',
                        title: 'Click to Generate Bundle',
                        content: 'Click to Generate Bundle',
                        placement: 'top',
                        arrowOffset: 5
                    },
                    {
                        target: 'bundle-tour-4',
                        title: 'Click to View Generated Bundle',
                        content: 'Click to View Generated Bundle',
                        placement: 'top',
                        arrowOffset: 5
                    },
                ],
                showPrevButton: true,
            },
            addClickListener = function(el, fn) {
                if (el.addEventListener) {
                    el.addEventListener('click', fn, false);
                } else {
                    el.attachEvent('onclick', fn);
                }
            },
            init = function() {
                var startBtnId = 'bundle-tour-button',
                    calloutId = 'startTourCallout',
                    mgr = hopscotch.getCalloutManager(),
                    state = hopscotch.getState();

                addClickListener(document.getElementById(startBtnId), function() {
                    if (!hopscotch.isActive) {
                        mgr.removeAllCallouts();
                        hopscotch.startTour(tour);
                    }
                });
            };
        init();
        init();
    </script>
@endpush
