@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex">
        <h3>Komentar</h3>
      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Avatar</th>
            <th>Nama</th>
            <th>Publik</th>
            <th>Komentar</th>
            <th>Dibuat</th>
            <th class="text-center" style="width: 140px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($comments as $comment)
            <tr>
              <td class="align-middle">
                <span class="avatar">
                  <img width="30px" src="{{ $comment->user->avatar_url }}" />
                </span>
              </td>
              <td class="align-middle text-nowrap font-weight-bold"><a href="{{ route('users.show', $comment->user->id) }}">{{ $comment->user->name }}</a></td>
              <td class="align-middle text-nowrap font-weight-bold">{{ $comment->public ? 'Ya' : 'Tidak' }}</td>
              <td class="align-middle">{{ $comment->body }}</td>
              <td class="align-middle text-nowrap font-weight-bold">{{ $comment->created_at->format('d-m-Y') }}</td>
              <td class="text-center py-0 align-middle text-nowrap">
                @if (!$comment->public)
                  <a href="{{ route('comments.make-public', $comment->id) }}" class="btn btn-primary btn-sm">
                    Publikasi
                  </a>
                @endif
                <button class="btn btn-danger btn-sm" delete-confirm="#form-delete-comment-{{ $comment->id }}">
                  Hapus
                </button>
                <form action="{{ route('comments.destroy', $comment->id) }}" method="post" id="form-delete-comment-{{ $comment->id }}">
                  @csrf
                  @method('delete')
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="card-footer">
        {{ $comments->links() }}
      </div>
    </div>
  </div>
@endsection
