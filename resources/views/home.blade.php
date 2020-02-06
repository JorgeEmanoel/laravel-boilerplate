@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            Dashboard
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach (Auth::user()->profiles as $profile)
                        <p>{{ $profile->name }}</p>
                    @endforeach

                    @if (Auth::user()->isSuperAdmin())
                        <br>
                        <a href="{{ route('acl.index') }}">ACL Manager</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
