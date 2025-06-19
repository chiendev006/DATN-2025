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
                            <h2 class="text-coffee">Liên hệ với chúng tôi</h2>
                            <h6  style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">Chúng tôi luôn đặt lợi ích của khách hàng lên hàng đầu nếu bạn có thắc măc gì hãy liên hệ với chúng tôi.</h6>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <h5 class="text-coffee">Để lại cho chúng tôi mộ tin nhắn</h5>
                                 <div id="success-message"></div>
                                <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
                                  @csrf
                                    <div class="alert-container"></div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>Họ tên <span class="text-danger">*</span></label>
                                            <input name="name" type="text" value="{{ old('name') }}" placeholder="Nhập họ và tên" class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input name="email" type="email" value="{{ old('email') }}" placeholder="Nhập email" class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <label>Số điện thoại <span class="text-muted">(Không bắt buộc)</span></label>
                                            <input name="phone" type="text" value="{{ old('phone') }}" placeholder="Nhập số điện thoại (10-11 số)" class="form-control @error('phone') is-invalid @enderror">
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label>Tin nhắn <span class="text-danger">*</span></label>
                                          <textarea id="editor" name="message" placeholder="Nhập tin nhắn của bạn (ít nhất 10 ký tự)" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                          @error('message')
                                              <span class="text-danger">{{ $message }}</span>
                                          @enderror
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <button name="submit" class="submit-btn send_message" type="submit">Gửi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
                                <address>
                                    <span class="text-primary co-title">Địa chỉ của chúng tôi</span>
                                    <p>55 Lương Khánh Thiện, Ngô Quyền, Hải Phòng</p>
                                    <p><a href="tel:1234567890">0377 888 999</a> <br> <a href="mailto:support@despina.com">mira99@gmail.com</a></p>
                                </address>
                                <h5 class="text-coffee">Giờ mở cửa</h5>
                                <ul class="time-list">
                                    <li><span class="week-name">Thứ hai</span> <span>12-6 giờ</span></li>
                                    <li><span class="week-name">Thứ ba</span> <span>12-6 giờ</span></li>
                                    <li><span class="week-name">Thứ tư</span> <span>12-6 giờ</span></li>
                                    <li><span class="week-name">Thứ năm</span> <span>12-6 giờ</span></li>
                                    <li><span class="week-name">Thứ sáu</span> <span>12-6 giờ</span></li>
                                    <li><span class="week-name">Thứ bảy</span> <span>12-6 giờ</span></li>
                                    <li><span class="week-name">Chủ nhật</span> <span>Đóng cửa</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </main>
        <style>
        .text-danger {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .text-muted {
            color: #6c757d;
            font-size: 0.875rem;
        }

        /* Validation styles */
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .form-control.is-invalid {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='m5.8 4.6 2.4 2.4m0-2.4L5.8 7'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        /* Label styles */
        label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* field indicator */
        .text-danger {
            font-weight: bold;
        }

        /* Form control styles */
        .form-control {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #c7a17a;
            box-shadow: 0 0 0 0.2rem rgba(199, 161, 122, 0.25);
            outline: 0;
        }

        /* Summernote editor validation */
        .note-editor.note-frame.is-invalid {
            border-color: #dc3545 !important;
        }

        .note-editor.note-frame.is-invalid .note-editing-area {
            border-color: #dc3545 !important;
        }

        /* Success/Error message styles */
        #success-message div {
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        /* Form spacing */
        .col-md-6, .col-sm-6, .col-xs-12 {
            margin-bottom: 1rem;
        }

        /* Submit button styles */
        .submit-btn {
            background-color: #c7a17a;
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: #b08d5f;
            color: white;
        }
        </style>
        @section('scripts')
        <script>
  $(document).ready(function() {
    $('#editor').summernote({
      height: 300,
      placeholder: 'Nhập tin nhắn của bạn (ít nhất 10 ký tự)...',
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("contact-form");
  const messageBox = document.getElementById("success-message");

  // Client-side validation functions
  function validateName(name) {
    if (!name || name.trim().length < 2) {
      return 'Họ tên phải có ít nhất 2 ký tự';
    }
    if (name.length > 255) {
      return 'Họ tên không được quá 255 ký tự';
    }
    return null;
  }

  function validateEmail(email) {
    if (!email) {
      return 'Vui lòng nhập email';
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return 'Email không đúng định dạng';
    }
    if (email.length > 255) {
      return 'Email không được quá 255 ký tự';
    }
    return null;
  }

  function validatePhone(phone) {
    if (phone && phone.trim()) {
      const phoneRegex = /^[0-9]{10,11}$/;
      if (!phoneRegex.test(phone)) {
        return 'Số điện thoại phải có 10-11 chữ số';
      }
      if (phone.length > 20) {
        return 'Số điện thoại không được quá 20 ký tự';
      }
    }
    return null;
  }

  function validateMessage(message) {
    if (!message || message.trim().length < 10) {
      return 'Tin nhắn phải có ít nhất 10 ký tự';
    }
    if (message.length > 1000) {
      return 'Tin nhắn không được quá 1000 ký tự';
    }
    return null;
  }

  function showError(field, message) {
    field.classList.add('is-invalid');
    let errorElement = field.parentNode.querySelector('.text-danger');
    if (!errorElement) {
      errorElement = document.createElement('span');
      errorElement.className = 'text-danger';
      field.parentNode.appendChild(errorElement);
    }
    errorElement.textContent = message;
  }

  function clearError(field) {
    field.classList.remove('is-invalid');
    const errorElement = field.parentNode.querySelector('.text-danger');
    if (errorElement) {
      errorElement.remove();
    }
  }

  // Real-time validation
  const nameInput = document.querySelector('input[name="name"]');
  const emailInput = document.querySelector('input[name="email"]');
  const phoneInput = document.querySelector('input[name="phone"]');

  nameInput.addEventListener('blur', function() {
    const error = validateName(this.value);
    if (error) {
      showError(this, error);
    } else {
      clearError(this);
    }
  });

  emailInput.addEventListener('blur', function() {
    const error = validateEmail(this.value);
    if (error) {
      showError(this, error);
    } else {
      clearError(this);
    }
  });

  phoneInput.addEventListener('blur', function() {
    const error = validatePhone(this.value);
    if (error) {
      showError(this, error);
    } else {
      clearError(this);
    }
  });

  // Summernote validation
  $('#editor').on('summernote.blur', function() {
    const content = $(this).summernote('code').replace(/<[^>]*>/g, '').trim();
    const error = validateMessage(content);
    const editor = $(this).next('.note-editor');

    if (error) {
      editor.addClass('is-invalid');
      let errorElement = editor.parent().find('.text-danger');
      if (errorElement.length === 0) {
        errorElement = $('<span class="text-danger"></span>');
        editor.parent().append(errorElement);
      }
      errorElement.text(error);
    } else {
      editor.removeClass('is-invalid');
      editor.parent().find('.text-danger').remove();
    }
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Clear previous messages
    messageBox.innerHTML = '';

    // Validate all fields
    let hasErrors = false;

    const nameError = validateName(nameInput.value);
    if (nameError) {
      showError(nameInput, nameError);
      hasErrors = true;
    }

    const emailError = validateEmail(emailInput.value);
    if (emailError) {
      showError(emailInput, emailError);
      hasErrors = true;
    }

    const phoneError = validatePhone(phoneInput.value);
    if (phoneError) {
      showError(phoneInput, phoneError);
      hasErrors = true;
    }

    const messageContent = $('#editor').summernote('code').replace(/<[^>]*>/g, '').trim();
    const messageError = validateMessage(messageContent);
    if (messageError) {
      const editor = $('#editor').next('.note-editor');
      editor.addClass('is-invalid');
      let errorElement = editor.parent().find('.text-danger');
      if (errorElement.length === 0) {
        errorElement = $('<span class="text-danger"></span>');
        editor.parent().append(errorElement);
      }
      errorElement.text(messageError);
      hasErrors = true;
    }

    if (hasErrors) {
      // Scroll to first error
      const firstError = document.querySelector('.is-invalid');
      if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      return;
    }

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
        $('#editor').summernote('reset');
        // Clear all validation errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.text-danger').forEach(el => el.remove());
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
