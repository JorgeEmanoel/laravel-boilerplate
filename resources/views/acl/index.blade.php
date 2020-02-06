@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{ route('home') }}">Dashboard</a> /
            Acl
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">ACL Manager</div>

                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="{{ route('acl.modules.index') }}">Manage modules</a> <br>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('acl.profiles.index') }}">Manage profiles</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
