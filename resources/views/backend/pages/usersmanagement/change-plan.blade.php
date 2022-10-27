@extends('backend.layouts.app')

@section('template_title')
    {!! trans('usersmanagement.change-plan', ['name' => $user->name]) !!}
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h1>Current Plan : {{ $enrolled_package->package->name }}</h1>
                <form action="{{ route('users.change_plan.update', $user->id) }}" method="post">
                    @csrf
                    <label for="">PLAN</label>
                    <select name="plan" id="" required class="form-control">
                        <option value="">SELECT PACKAGE</option>
                        @foreach ($package as $p)
                            <option value="{{ $p->id }}" @if ($p->id == $enrolled_package->package->id) selected @endif>
                                {{ $p->name }}</option>
                        @endforeach
                    </select>
                    <br>
                    <input type="submit" value="UPDATE PLAN" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @include('scripts.delete-modal-script')
    @if (config('usersmanagement.tooltipsEnabled'))
        @include('scripts.tooltips')
    @endif
@endsection
