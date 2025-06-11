<!-- Core JS files -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script src="{{ asset('assets/js/app.js') }}"></script>
<!-- /theme JS files -->

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

@yield('scripts')
</body>
</html>
