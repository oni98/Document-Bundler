@extends('backend.layouts.app')

@section('template_title')
    {{ Auth::user()->name }}'s' Bundle
@endsection

@push('custom-css')
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    @php
    $enrolled_package = auth()
        ->user()
        ->load('enrolledPackage')->enrolledPackage;
    @endphp

    <div style="display: none">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Bundle</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item ">Bundle</li>
                            <li class="breadcrumb-item active">Index</li>
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

            <div class="row mt-3 align-items-center">
                <div class="col-lg-3"></div>
                <div class="col-lg-6 text-center">
                    @if ($enrolled_package->package_id == 1)
                        <div class="card bg-danger mx-5 mb-0">
                            <div class="card-body p-2">
                                You are now in free Plan. Please Upgrade Your Plan.
                                <a href="{{ route('public.choosePlan') }}" class="btn btn-dark ml-3">UPGRADE</a>
                            </div>
                        </div>
                    @elseif ($enrolled_package->package_id == 2)
                        <div class="card bg-primary mx-5 mb-0">
                            <div class="card-body p-2">
                                UPGRADE TO UNLIMITED
                                <a href="{{ route('public.choosePlan') }}" class="btn btn-success ml-3">UPGRADE</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-3 text-right">
                    <button type="button" class="btn btn-lg btn-warning" id="bundle-tour-button">
                        <i class="fa fa-question"></i>
                    </button>
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">

                    <div class="mb-3">
                        <button type="button" class="btn btn-lg btn-primary" data-toggle="modal"
                            data-target="#modal-default" id="bundle-tour-title">
                            <i class="fa fa-plus"></i>
                            New Bundle
                        </button>
                    </div>

                    <div class="modal fade" id="modal-default">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">New Bundle</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if ($enrolled_package->package_id == 1)
                                    @if (count($bundle) == 0)
                                        <form action="{{ route('bundle.store') }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="text" placeholder="Bundle Name" class="form-control"
                                                    name="name" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Close</button>
                                                <input type="submit" class="btn btn-primary" value="Create" />
                                            </div>
                                        </form>
                                    @else
                                        <div class="modal-body text-danger">
                                            You are now in free Plan. Please Upgrade Your Plan to Create more Bundle
                                        </div>

                                        <div class="modal-footer justify-content-start">
                                            <a href="{{ route('public.choosePlan') }}" class="btn btn-danger">UPGRADE</a>
                                        </div>
                                    @endif
                                @elseif($enrolled_package->package_id == 2)
                                    @if (intval(count($bundle)) < 5)
                                        <form action="{{ route('bundle.store') }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="text" placeholder="Bundle Name" class="form-control"
                                                    name="name" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-dismiss="modal">Close</button>
                                                <input type="submit" class="btn btn-primary" value="Create" />
                                            </div>
                                        </form>
                                    @endif
                                @else
                                    <form action="{{ route('bundle.store') }}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="text" placeholder="Bundle Name" class="form-control"
                                                name="name" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                            <input type="submit" class="btn btn-primary" value="Create" />
                                        </div>
                                    </form>
                                @endif
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    @if (Session::has('message'))
                        <div class="alert alert-success">
                            <h4>{{ Session::get('message') }}</h4>
                        </div>
                    @endif

                    <div style="display: none;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bundle Name</th>
                                    <th>Created</th>
                                    <th>Total Page</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($bundle as $b)
                                    <tr>
                                        <td>
                                            {{ $b->name }}
                                        </td>
                                        <td>{{ $b->formatdate() }}</td>
                                        <td>
                                            {{ $b->totalPages() }}
                                        </td>
                                        <td>
                                            @if ($enrolled_package->package_id == 1)
                                                @if ($b->totalPages() > 60)
                                                    <span class="text-danger">You do not have permission to generate
                                                        bundle
                                                        more then 60 pages</span><br>
                                                @endif
                                            @endif
                                            <a href="{{ route('bundle.show_single', [$b->slug, $b->id]) }}"
                                                class="btn btn-outline-primary"><i class="fa fa-eye"></i> VIEW</a>
                                            <a href="{{ route('bundle.edit', $b->id) }}"
                                                class="btn btn-outline-primary"><i class="fa fa-pencil"></i> RENAME</a>
                                            @if ($enrolled_package->package_id == 1)
                                                @if ($b->totalPages() < 60)
                                                    @if ($b->generated->count() == 0)
                                                        <a href="{{ route('public.bundle.generate', [$b->id]) }}"
                                                            class="btn btn-outline-info">Generate Bundle</a>
                                                    @endif
                                                @else
                                                    <a href="#" class="btn btn-outline-info">Generate
                                                        Bundle</a>
                                                @endif
                                            @else
                                                @if ($b->generated->count() == 0)
                                                    <a href="{{ route('public.bundle.generate', [$b->id]) }}"
                                                        class="btn btn-outline-info">Generate Bundle</a>
                                                @endif
                                            @endif
                                            @if ($enrolled_package->package_id == 1)
                                                @if ($b->totalPages() < 60)
                                                    <a href="{{ route('public.bundle.generated_bundle', [$b->id]) }}"
                                                        class="btn btn-outline-info">View Generated Bundle</a>
                                                @else
                                                    <a href="#" class="btn btn-outline-info">View Generated
                                                        Bundle</a>
                                                @endif
                                            @else
                                                <a href="{{ route('public.bundle.generated_bundle', [$b->id]) }}"
                                                    class="btn btn-outline-info">View Generated Bundle</a>
                                            @endif
                                            <form action="{{ route('bundle.destroy', [$b->id]) }}" method="post">
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

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Bundle Name</th>
                                <th>Created</th>
                                <th>Total Page</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bundle as $b)
                                <tr class="clickable-row"
                                    data-href="{{ route('bundle.show_single', [$b->slug, $b->id]) }}">
                                    <a href="">
                                        <td class="py-1 pl-3 align-middle">
                                            {{ $b->name }}
                                        </td>

                                        <td class="py-1 pl-3 align-middle">{{ $b->formatdate() }}</td>

                                        <td class="py-1 pl-3 align-middle">
                                            {{ $b->totalPages() }}
                                        </td>

                                        <td class="py-1 pl-3 align-middle text-right">
                                            <a title="View"
                                                href="{{ route('bundle.show_single', [$b->slug, $b->id]) }}"
                                                class="text-white d-inline-block" data-toggle="tooltip"
                                                data-placement="top">
                                                <button class="btn btn-primary"><i class="fa fa-eye"></i></button>
                                            </a>

                                            <a title="Rename" href="{{ route('bundle.edit', $b->id) }}"
                                                data-toggle="tooltip" data-placement="top" class="d-inline-block">
                                                <button class="btn btn-primary"><i
                                                        class="fa fa-pencil-square-o"></i></button>
                                            </a>

                                            <form title="Delete" action="{{ route('bundle.destroy', [$b->id]) }}"
                                                method="post" class="d-inline-block" data-toggle="tooltip"
                                                data-placement="top">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        // tour setup
        var tour = {
                id: 'bundle-tour',
                steps: [{
                    target: 'bundle-tour-title',
                    title: 'Click to Create New Bundle',
                    content: 'Click Here to create new bundle',
                    placement: 'right',
                    arrowOffset: 5
                }, ],
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
    </script>
@endpush
