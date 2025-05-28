
@extends('layout2')
@section('main')
<main>
            <div class="main-part">

                <section class="breadcrumb-nav">
                    <div class="container">
                        <div class="breadcrumb-nav-inner">
                            <ul>
                                <li><a href="/">Home</a></li>
                                <li class="active"><a href="#">Contact</a></li>
                            </ul>
                            <label class="now">Contact</label>
                        </div>
                    </div>
                </section>

                <!-- Start Contact Part -->

                <section class="default-section contact-part pad-top-remove">
                    
                    <div class="container">
                        <div class="title text-center">
                            <h2 class="text-coffee">Contact Us</h2>
                            <h6>We are a second-generation family business established in 1972</h6>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <h5 class="text-coffee">Leave us a Message</h5>
                                <p>Aenean massa diam, viverra vitae luctus sed, gravida eget est. Etiam nec ipsum porttitor, consequat libero eu, dignissim eros. Nulla auctor lacinia enim id mollis.</p>
                                 <div id="success-message"></div>
                                <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
                                  @csrf
                                    <div class="alert-container"></div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>Full Name</label>
                                            <input name="name" type="text" required>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>Email</label>
                                            <input name="email" type="email" required>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label>Your Message *</label>
                                             <input name="massage" type="text" required>
                                        </div>  
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <button name="submit" class="submit-btn send_message" type="submit">Gửi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <address>
                                    <span class="text-primary co-title">Our Address</span>
                                    <p>329 Queensberry Street, North Melbourne  VIC 3051, Australia.</p>
                                    <p><a href="tel:1234567890">123 456 7890</a> <br> <a href="mailto:support@despina.com">support@despina.com</a></p>
                                </address>
                                <h5 class="text-coffee">Opening Hours</h5>
                                <ul class="time-list">
                                    <li><span class="week-name">Monday</span> <span>12-6 PM</span></li>
                                    <li><span class="week-name">Tuesday</span> <span>12-6 PM</span></li>
                                    <li><span class="week-name">Wednesday</span> <span>12-6 PM</span></li>
                                    <li><span class="week-name">Thursday</span> <span>12-6 PM</span></li>
                                    <li><span class="week-name">Friday</span> <span>12-6 PM</span></li>
                                    <li><span class="week-name">Saturday</span> <span>12-6 PM</span></li>
                                    <li><span class="week-name">Sunday</span> <span>Closed</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- End Contact Part -->

            </div>
        </main> 
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
       @endsection