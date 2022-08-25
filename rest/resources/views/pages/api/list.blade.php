@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('client'))
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Client details</div>

                <div class="card-body">
                    <p>Client ID: {{ session('client')['client_id'] }}</p>
                    <p>Client Name: {{ session('client')['name'] }}</p>
                    <p>Client Secret: {{ session('client')['client_secret'] }}</p>

                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create API</div>

                <div class="card-body">
                    <form action="{{ route('api.createClient') }}" method="post" enctype="application/x-www-form-urlencoded">
                        @csrf
                        <div class="mb-3">
                            <label for="userid" class="form-label">User ID</label>
                            <input type="text" class="form-control" name="userId" id="userid" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="userName" id="name" placeholder="" required>
                        </div>
                        <div class="mb-3">
                            <label for="redirect" class="form-label">Redirect URL</label>
                            <input type="text" class="form-control" name="redirectUrl" id="redirect" value="http://domain.com/callback">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">List API</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Client ID</td>
                                    <td>User ID</td>
                                    <td>Name</td>
                                    <td>Redirect URL</td>
                                    <td>Type</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $cl)
                                <tr>
                                    <td>{{ $cl->id }}</td>
                                    <td>{{ $cl->user_id }}</td>
                                    <td>{{ $cl->name }}</td>
                                    <td>{{ $cl->redirect }}</td>
                                    @if (null == $cl->secret && $cl->personal_access_client == false)
                                    <td>
                                        <span class="badge bg-primary">public</span>
                                    </td>
                                    @endif
                                    @if (null !== $cl->secret && $cl->personal_access_client == false)
                                    <td>
                                        <span class="badge bg-success">client</span>
                                    </td>
                                    @endif
                                </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
