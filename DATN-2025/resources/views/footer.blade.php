<!-- App footer start -->
					<div class="app-footer">© Uni Pro Admin 2021</div>
					<!-- App footer end -->

				</div>
				<!-- Content wrapper scroll end -->

			</div>
			<!-- *************
				************ Main container end *************
			************* -->

		</div>
		<!-- Page wrapper end -->

		<!-- *************
			************ Required JavaScript Files *************
		************* -->
		<!-- Required jQuery first, then Bootstrap Bundle JS -->
		<script src="{{ url('assetadmin') }}/js/jquery.min.js"></script>
		<script src="{{ url('assetadmin') }}/js/bootstrap.bundle.min.js"></script>
		<script src="{{ url('assetadmin') }}/js/modernizr.js"></script>
		<script src="{{ url('assetadmin') }}/js/moment.js"></script>

		<!-- *************
			************ Vendor Js Files *************
		************* -->

		<!-- Megamenu JS -->
		<script src="{{ url('assetadmin') }}/vendor/megamenu/js/megamenu.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/megamenu/js/custom.js"></script>

		<!-- Slimscroll JS -->
		<script src="{{ url('assetadmin') }}/vendor/slimscroll/slimscroll.min.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/slimscroll/custom-scrollbar.js"></script>

		<!-- Search Filter JS -->
		<script src="{{ url('assetadmin') }}/vendor/search-filter/search-filter.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/search-filter/custom-search-filter.js"></script>

		<!-- Apex Charts -->
		{{--
		<script src="{{ url('assetadmin') }}/vendor/apex/apexcharts.min.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/apex/custom/home/salesGraph.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/apex/custom/home/ordersGraph.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/apex/custom/home/earningsGraph.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/apex/custom/home/visitorsGraph.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/apex/custom/home/customersGraph.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/apex/custom/home/sparkline.js"></script>
		--}}

		<!-- Circleful Charts -->
		<script src="{{ url('assetadmin') }}/vendor/circliful/circliful.min.js"></script>
		<script src="{{ url('assetadmin') }}/vendor/circliful/circliful.custom.js"></script>

		<!-- Main Js Required -->
		<script src="{{ url('assetadmin') }}/js/main.js"></script>

        <script>
            // Function to handle delete operations via POST method
            function deleteViaPost(route, message = 'Bạn có chắc chắn muốn xóa?') {
                if (confirm(message)) {
                    // Create a form element
                    var form = document.createElement('form');
                    form.setAttribute('method', 'post');
                    form.setAttribute('action', route);

                    // Add CSRF token
                    var csrfToken = document.createElement('input');
                    csrfToken.setAttribute('type', 'hidden');
                    csrfToken.setAttribute('name', '_token');
                    csrfToken.setAttribute('value', '{{ csrf_token() }}');
                    form.appendChild(csrfToken);

                    // Append form to body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
                return false;
            }
        </script>
</div>
	</body>
  <!-- jQuery (bắt buộc) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">


<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
<!-- Mirrored from www.bootstrapget.com/demos/themeforest/unipro-admin-template/demos/01-design-blue/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 25 May 2025 08:58:21 GMT -->

</html>
