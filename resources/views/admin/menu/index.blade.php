@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">{{ __('menu.title') }}</div>
                    <div class="card-body">
                        <a href="{{ url('/admin/menu/create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> {{ __('add') }}
                        </a>
                        @if(Session::has('flash_message'))
                            <div id="message" class="alert alert-success"><span class="fa fa-check"></span><em> {{ session('flash_message') }}</em></div>
                        @endif
                        @if(Session::has('flash_danger'))
                            <div id="message" class="alert alert-danger"><span class="fa fa-check"></span><em> {{ session('flash_danger') }}</em></div>
                        @endif
                        {{ Form::open(['method' => 'GET', 'url' => '/admin/menu', 'class' => 'form-inline my-2 my-lg-0 float-right', 'menu' => 'search'])  }}
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="{{ __('search') }}" value="{{ request('search') }}">
                            <span class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        {{ Form::close() }}

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>{{ __('name') }}</th><th>{{ __('actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($menu as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            <a href="{{ url('/admin/menu/' . $item->id) }}"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('show') }}</button></a>
                                            <a href="{{ url('/admin/menu/' . $item->id . '/edit') }}"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('edit') }}</button></a>
                                            {{ Form::open([
                                                'method' => 'DELETE',
                                                'url' => ['/admin/menu', $item->id],
                                                'style' => 'display:inline'
                                            ]) }}
                                                {{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>' . __('delete') , [
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'onclick' => 'return confirm(' . __('notification.delete') . ')'
                                                ]) }}
                                            {{ Form::close() }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {{ $menu->appends(['search' => Request::get('search')])->render() }} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
