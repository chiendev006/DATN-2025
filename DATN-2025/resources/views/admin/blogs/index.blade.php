@include('header')

<div class="control"></div>
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
    Blog
    </h1>
  </div>
</section>
  <div class="control">
    <button type="submit" class="button green">
        <a href="{{ route('blogs.create') }}">Thêm Blog</a>
    </button>
            </div> 
<div class="card has-table">
  <header class="card-header">
    <p class="card-header-title">
      <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
      Clients
    </p>
    <a href="/admin/danhmuc" class="card-header-icon">
      <span class="icon"><i class="mdi mdi-reload"></i></span>
    </a>
  </header>

  <div class="card-content">
    @if (session('success'))
      <div style="color: green; background-color: #e6ffe6; padding: 10px; margin-bottom: 10px;">
        {{ session('success') }}
      </div>
    @endif

    <table>
      <thead>
        <tr>
          <th class="checkbox-cell">
            <label class="checkbox">
              <input type="checkbox">
              <span class="check"></span>
            </label>
          </th>
          <th class="image-cell"></th>
          <th>ID</th>
          <th>Title</th>
          <th>Created At</th>
          <th>Content</th>
          <th>Image</th>
          <th>Updated At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($blogs as $item)
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>

            <td class="image-cell"></td>

            <td>{{ $item->id }}</td>
            <td>{{ $item->title }}</td>
            <td>{{ $item->content }}</td>
            {{-- <td>{{ $item->image }}</td> --}}
            <td>
    @if ($item->image)
        <img src="{{ asset('storage/' . $item->image) }}" width="100" alt="Blog image">
    @else
        Không có ảnh
    @endif
</td>

            <td>
              {{ $item->created_at ? $item->created_at->format('d/m/Y') : 'N/A' }}
            </td>
            <td>
              {{ $item->updated_at ? $item->updated_at->format('d/m/Y') : 'N/A' }}
            </td>

            <td class="actions-cell">
                 <a href="{{ route('blogs.edit', ['id' => $item->id]) }}" class="button small blue">
                    <span class="icon"><i class="mdi mdi-pencil">Sửa</i></span>
                </a>

              <a href="{{ route('blogs.destroy', ['id' => $item->id]) }}" 
                 onclick="return confirm('Bạn có chắc chắn muốn xóa ?')" 
                 class="button small red">
                <span class="icon">
                  <i class="mdi mdi-trash-can">Xóa</i>
                </span>
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="table-pagination mt-4">
      <div class="flex items-center justify-between">
        <div class="buttons">
          @for ($i = 1; $i <= $blogs->lastPage(); $i++)
            <a href="{{ $blogs->url($i) }}">
              <button type="button" class="button {{ $i == $blogs->currentPage() ? 'active' : '' }}">
                {{ $i }}
              </button>
            </a>
          @endfor
        </div>
        <small>Page {{ $blogs->currentPage() }} of {{ $blogs->lastPage() }}</small>
      </div>
    </div>
  </div>
</div>

@include('footer')
