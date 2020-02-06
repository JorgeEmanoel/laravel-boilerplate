@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="{{ route('home') }}">Dashboard</a> /
            <a href="{{ route('acl.index') }}">Acl</a> /
            <a href="{{ route('acl.modules.index') }}">Modules</a> /
            {{ $module->display_name }}
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title">
                {{ $module->display_name }} ({{ $module->name }})
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-lg-3">
                    <ul>
                        <li><strong>Available Profiles:</strong> {{ $profiles->count() }}</li>
                        <li><strong>Module's Permissions:</strong> {{ $module->permissions->count() }}</li>
                    </ul>

                    Module's Permissions Levels

                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a href="#tab-profiles" data-toggle="tab" class="list-group-item">
                                Attached profiles
                            </a>
                        </li>
                        @foreach ($acl->getLevels() as $level)
                            <li>
                                <a href="#tab-level-{{ $level }}" data-toggle="tab" class="list-group-item">
                                    {{ $acl->getLevelTranslated($level) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                {{-- /.col-lg-3 --}}

                <div class="col-lg-9">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab-profiles">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Attached profiles</h4>
                                </div>
                                {{-- /.panel-heading --}}

                                <div class="panel-body">
                                    <form class="form" action="{{ route('acl.modules.updateProfiles', $module->id) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}

                                        <div class="form-group">
                                            <select name="profiles[]" id="i_profiles" class="form-control select2" multiple>
                                                @foreach ($profiles as $profile)
                                                    <option{{ $module->profiles->contains($profile) ? ' selected' : '' }} value="{{ $profile->id }}">
                                                        {{ $profile->name }} ({{ $profile->users->count() }} users)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">
                                                Save profiles
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                {{-- /.panel-body --}}
                            </div>
                        </div>
                        @foreach ($acl->getLevels() as $level)
                            <div class="tab-pane fade" id="tab-level-{{ $level }}">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">{{ $acl->getLevelTranslated($level) }}</h4>
                                    </div>
                                    {{-- /.panel-header --}}

                                    <div class="panel-body">
                                        @foreach ($module->profiles as $profile)
                                            <div class="panel">
                                                <div class="panel-body">
                                                    <form class="form" action="{{ route('acl.profiles.updatePermissions', $profile->id) }}" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PUT') }}

                                                        <strong>{{ $profile->name }}</strong> <br>

                                                        @foreach ($module->getLevelPermissions($level) as $permission)
                                                            <label for="{{ $level }}-{{ $profile->id }}-{{ $permission->name }}-{{ $permission->id }}" class="btn btn-sm btn-default">
                                                                <input type="checkbox"{{ $profile->hasPermission($permission) ? ' checked' : '' }} name="permissions[]" id="{{ $level }}-{{ $profile->id }}-{{ $permission->name }}-{{ $permission->id }}" value="{{ $permission->id }}">
                                                                {{ $permission->getName() }}
                                                            </label>
                                                        @endforeach

                                                        <div class="form-group"><br>
                                                            <button type="submit" class="btn btn-success">
                                                                Save permissions for <strong>"{{ $profile->name }}"</strong> profile
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                                {{-- /.panel-body --}}
                                            </div>
                                            {{-- /.panel --}}
                                        @endforeach
                                    </div>
                                    {{-- /.panel-header --}}
                                </div>
                                {{-- /.panel --}}
                            </div>
                            {{-- /.tab-pane --}}
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
