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
                    <h1 class="m-0">Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item ">Settings</li>
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Setting Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($setting)>0)
                                        @foreach ($setting as $item)

                                        @if($item->name == "watermark_setting")
                                        <tr>
                                            <td>Watermark Setting</td>
                                            @if($item->value == 1)
                                                <td>Enable</td>
                                            @else
                                                <td>Disabled</td>
                                            @endif
                                        </tr>
                                        @endif
                                        @if($watermark_setting->value == 1)
                                            @if($item->name == "watermark")
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                @if($item->type == "IMG")
                                                <td><img src="{{ asset('watermark/'.$item->value) }}" class="img-responsive" alt="" ></td>
                                                @else

                                                <td>{{ $item->value }}</td>
                                                @endif

                                            </tr>
                                            @endif
                                        @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>Watermark</td>
                                            <td>Disabled</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Settings
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
                            <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select class="form-control" name="values"required>
                                            <option value="">Watermark Option</option>
                                            <option value="1"  @if(!is_null($watermark_setting)) @if($watermark_setting->value == 1) selected @endif @endif>Enable</option>
                                            <option value="0"  @if(!is_null($watermark_setting)) @if($watermark_setting->value == 2) selected @endif @else selected @endif>Disabled</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4"><input type="submit" class="btn btn-success" value="Submit" />
                                    </div>
                                    <div class="col-sm-12">
                                        <br>
                                    </div>
                                </div>
                            </form>
                            @if(!is_null($watermark_setting))
                             @if($watermark_setting->value == 1)
                                <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <select class="form-control" name="type" id="type" required>
                                                <option value="">SELECT A TYPE</option>
                                                <option value="TEXT">TEXT</option>
                                                <option value="IMG">IMAGE</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4" id="datas">
                                            <div id="text">

                                            </div>
                                            <div id="img">

                                            </div>
                                        </div>
                                        <div class="col-sm-4"><input type="submit" class="btn btn-success" value="Submit" />
                                        </div>
                                    </div>
                                </form>
                            @endif
                            @endif
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
<script>
    $(document).ready(function(){
        $('#text').empty();
        $('#img').empty();
        $('#datas').hide();
        $("#type").change(function(){
            var values = $(this).val();
            if(values == "TEXT"){

                $('#datas').show();
                $('#text').append('<input type="text" name="values" @if(!is_null($watermark))  value="{{ $watermark->value }}" @endif class="form-control" id="" required>');
                $('#img').empty();
            }else{
                $('#datas').show();
                $('#img').append('<input type="file" name="values" class="form-control" id="" required> <br>(267px X 104px)');
                $('#text').empty();
            }
        })
    })
</script>
@endpush
