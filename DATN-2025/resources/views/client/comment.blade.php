@extends('layout2')

@section('main')
<section class="default-section bg-grey">
    <div class="container">
        <div class="title text-center mb-4">
            <h3 class="text-coffee">Bình luận cho sản phẩm: <strong>{{ $sanpham->name }}</strong></h3>
        </div>
        <div class="mb-3 text-end" style="margin-left: 1035px; margin-bottom: 20px;">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-back-custom">
                <i class="fa fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>
        <div class="comment-blog bg-white p-4 rounded shadow-sm">
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
<style>
    .btn-back-custom {
        background: rebeccapurple;
        color: #fff !important;
        border: none;
        font-weight: 600;
        padding: 10px 28px;
        border-radius: 30px;
        font-size: 1rem;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
    }

    .btn-back-custom i {
        margin-right: 8px;
        font-size: 1.1em;
    }
    .comment-blog {
    background-color: #fff;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

</style>