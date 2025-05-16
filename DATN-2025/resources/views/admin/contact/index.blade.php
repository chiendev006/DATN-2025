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
         <div class="table-pagination mt-4">
          <div class="flex items-center justify-between">
            <div class="buttons">
              @for ($i = 1; $i <= $contact->lastPage(); $i++)
                <a href="{{ $contact->url($i) }}">
                  <button type="button" class="button {{ $i == $contact->currentPage() ? 'active' : '' }}">
                    {{ $i }}
                  </button>
                </a>
              @endfor
            </div>
            <small>Page {{ $contact->currentPage() }} of {{ $contact->lastPage() }}</small>
         </div>
        </div>
      </div>
    </div>
@include('footer')