@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{ route('home') }}">Dashboard</a> /
            <a href="{{ route('acl.index') }}">Acl</a> /
            Modules
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modules</div>

                <div class="card-body">
                    <div class="list-group">
                        @foreach ($modules as $module)
                            <a href="{{ route('acl.modules.show', $module->id) }}" class="list-group-item">
                                {{ $module->display_name }}
                                <small>({{ $module->name }})</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
