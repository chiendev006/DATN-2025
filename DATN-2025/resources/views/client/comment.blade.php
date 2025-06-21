@extends('layout2')

@section('main')
<section class="default-section bg-grey">
    <div class="container">
        <div class="title text-center mb-4">
            <h3 class="text-coffee">Bình luận cho sản phẩm: <strong>{{ $sanpham->name }}</strong></h3>
        </div>

        <div class="comment-blog">
            @if ($comments->count() > 0)
                @foreach ($comments as $item)
                    <div class="comment-inner-list d-flex mb-4">
                        <div class="comment-img mr-3">
                            <img src="{{ asset('storage/' . $item->user->image) }}" alt="User Image"
                                 style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                        </div>
                        <div class="comment-info">
                            <h5 class="mb-1">{{ $item->user->name }}</h5>
                            <span class="comment-date d-block mb-2 text-muted">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </span>
                            <p class="mb-0">{{ $item->comment }}</p>
                        </div>
                    </div>
                    <hr>
                @endforeach

                <div class="text-center mt-4">
                    {{ $comments->links() }}
                </div>
            @else
                <p class="text-center text-muted">Chưa có bình luận nào.</p>
            @endif
        </div>
    </div>
</section>
@endsection
