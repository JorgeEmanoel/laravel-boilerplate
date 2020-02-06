@extends('layouts.app')

@section('content')
<div class="modal fade" id="modal-addprofile">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close" aria-label="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title">New Profile</h4>
            </div>
            {{-- /.modal-header --}}

            <div class="modal-body">
                <form class="form" action="{{ route('acl.profiles.store') }}" method="post">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('name') ? ' has-error has-feedback' : '' }}">
                        <label for="i_addprofile-name" class="form-control-label">Name:</label>
                        <input type="text" class="form-control" id="i_addprofile-name" name="name" required maxlength="200">
                        @if ($errors->has('name'))
                            <div class="help-block">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('description') ? ' has-error has-feedback' : '' }}">
                        <label for="i_addprofile-description" class="form-control-label">Description (optional):</label>
                        <input type="text" class="form-control" id="i_addprofile-description" name="description" maxlength="255">
                        @if ($errors->has('description'))
                            <div class="help-block">{{ $errors->first('description') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-success">
                            Save
                        </button>
                    </div>
                </form>
            </div>
            {{-- /.modal-body --}}
        </div>
        {{-- /.modal-content --}}
    </div>
    {{-- /.modal-dialog --}}
</div>
{{-- /.modal --}}

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{ route('home') }}">Dashboard</a> /
            <a href="{{ route('acl.index') }}">Acl</a> /
            Profiles
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="panel">
                <div class="panel-heading">
                    Gerenciar perfis
                </div>

                <div class="panel-body">
                    <button type="button" class="btn btn-success form-control" data-toggle="modal" data-target="#modal-addprofile">
                        <i class="fa fa-plus"></i> Add profile
                    </button>
                </div>
            </div>
        </div>
        {{-- /.col-md-4 --}}

        <div class="col-md-8">
            <div class="panel">
                <div class="panel-heading">Profiles</div>

                <div class="panel-body">
                    <div class="list-group">
                        @foreach ($profiles as $profile)
                            <a href="{{ route('acl.modules.show', $profile->id) }}" class="list-group-item">
                                {{ $profile->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{-- /.col-md-8 --}}
    </div>
</div>
@endsection
