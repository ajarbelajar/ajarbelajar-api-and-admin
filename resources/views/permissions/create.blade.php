@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex">
        <h3>Buat Permission</h3>
        <div class="my-auto ml-auto">
          <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-primary">Batal</a>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('permissions.store') }}" method="post">
          @csrf
          <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama..." value="{{ old('name') }}">
            @error('name')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Buat Permission</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
