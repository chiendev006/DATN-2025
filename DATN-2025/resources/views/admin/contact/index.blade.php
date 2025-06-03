@include('header')
  <div class="control">
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
        <form method="GET" style="display:inline-block; margin-bottom: 10px;">
            <label for="per_page">Hiển thị</label>
            <select name="per_page" id="per_page" class="form-control" style="width: 80px; display:inline-block;" onchange="this.form.submit()">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select> bản ghi/trang
            @foreach(request()->except(['per_page','page']) as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
        </form>
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
            <th>Tên khách hàng</th>
            <th>Nội dung</th>
            <th>Ngày liên hệ</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($contact as $item)
          <tr>
            <td class="checkbox-cell">
              <label class="checkbox">
                <input type="checkbox">
                <span class="check"></span>
              </label>
            </td>
            <td class="image-cell">
            </td>
            <td data-label="Company">{{ $item['name'] }}</td>
            <td data-label="Company">{{ $item['massage'] }}</td>
            <td data-label="Company">{{ $item['created_at']->format('d/m/Y') }}</td>
            <td class="actions-cell">
               <a href="{{ route('contact.delete', ['id' => $item->id]) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa ?')" class="button small red">
                  <span class="icon">
                      <i class="mdi mdi-trash-can"></i>
                  </span>
              </a>
            </td>
            @endforeach
          </tbody>
        </table>
        @php
            $from = $contact->firstItem();
            $to = $contact->lastItem();
            $total = $contact->total();
            $currentPage = $contact->currentPage();
            $lastPage = $contact->lastPage();
        @endphp
        <div class="text-muted mb-2" style="font-size:13px;">
            Trang {{ $currentPage }}/{{ $lastPage }},
            Hiển thị {{ $from }}-{{ $to }}/{{ $total }} bản ghi
        </div>
        <div class="table-pagination mt-4">
          <div class="flex items-center justify-between">
            <div class="buttons">
              {{ $contact->appends(request()->except('page'))->links() }}
            </div>
            <small>Page {{ $contact->currentPage() }} of {{ $contact->lastPage() }}</small>
         </div>
        </div>
      </div>
    </div>
@include('footer')
