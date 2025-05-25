@extends('layout')
@section('main')
<section class="home-slider owl-carousel">
  <div class="slider-item" style="background-image: url('{{ asset('asset/images/bg_1.jpg') }}');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row slider-text justify-content-center align-items-center">
        <div class="col-md-7 col-sm-12 text-center ftco-animate">
          <h1 class="mb-3 mt-5 bread">Contact Us</h1>
          <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Contact</span></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section contact-section">
  <div class="container mt-5">
    <div class="row block-9">
      <div class="col-md-4 contact-info ftco-animate">
        <div class="row">
          <div class="col-md-12 mb-4">
            <h2 class="h4">Contact Information</h2>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>Address:</span> 198 West 21th Street, Suite 721 New York NY 10016</p>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>Phone:</span> <a href="tel://1234567920">+ 1235 2355 98</a></p>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>Email:</span> <a href="mailto:info@yoursite.com">info@yoursite.com</a></p>
          </div>
          <div class="col-md-12 mb-3">
            <p><span>Website:</span> <a href="#">yoursite.com</a></p>
          </div>
        </div>
      </div>

      <div class="col-md-1"></div>
      <div class="col-md-6 ftco-animate">
        <div id="success-message"></div>
        <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="contact-form">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Your Email" required>
              </div>
            </div>
          </div>
          <div class="form-group">
            <textarea name="massage" cols="30" rows="7" class="form-control" placeholder="Message" required></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary py-3 px-5">Send Message</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<div id="map"></div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("contact-form");
  const messageBox = document.getElementById("success-message");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);

    fetch(form.action, {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        messageBox.innerHTML = `<div style='color: green; background-color: #e6ffe6; padding: 10px;'>${data.message}</div>`;
        form.reset();
      } else {
        messageBox.innerHTML = `<div style='color: red; background-color: #ffe6e6; padding: 10px;'>${data.message || 'Gửi thất bại!'}</div>`;
      }
    })
    .catch(() => {
      messageBox.innerHTML = `<div style='color: red; background-color: #ffe6e6; padding: 10px;'>Đã xảy ra lỗi!</div>`;
    });
  });
});
</script>
@endsection
