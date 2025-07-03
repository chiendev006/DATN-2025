
					<div class="app-footer">© Uni Pro Admin 2021</div>


				</div>


			</div>


		</div>

		<script src="{{ url('assetadmin') }}/js/jquery.min.js"></script>
		<script src="{{ url('assetadmin') }}/js/bootstrap.bundle.min.js"></script>
		<script src="{{ url('assetadmin') }}/js/modernizr.js"></script>
		<script src="{{ url('assetadmin') }}/js/moment.js"></script>



		<script src="{{ url('assetadmin') }}/vendor/megamenu/js/megamenu.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/megamenu/js/custom.js"></script>


		<script src="{{ url('assetadmin') }}/vendor/slimscroll/slimscroll.min.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/slimscroll/custom-scrollbar.js"></script>


		<script src="{{ url('assetadmin') }}/vendor/search-filter/search-filter.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/search-filter/custom-search-filter.js"></script>


		<script src="{{ url('assetadmin') }}/vendor/circliful/circliful.min.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/circliful/circliful.custom.js"></script>


		<script src="{{ url('assetadmin') }}/js/main.js"></script>

        <script>
            function deleteViaPost(route, message = 'Bạn có chắc chắn muốn xóa?') {
                if (confirm(message)) {
                    var form = document.createElement('form');
                    form.setAttribute('method', 'post');
                    form.setAttribute('action', route);

                    var csrfToken = document.createElement('input');
                    csrfToken.setAttribute('type', 'hidden');
                    csrfToken.setAttribute('name', '_token');
                    csrfToken.setAttribute('value', '{{ csrf_token() }}');
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
                return false;
            }
        </script>
</div>



@if(isset($currentAdminId) && $currentAdminId)
    <div id="admin-app" data-current-user-id="{{ json_encode($currentAdminId) }}"></div>
@endif

	</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">


<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

</html>
