@include('header')
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
      Sản phẩm
    </h1>
  </div>
</section>
<div id="alert-container">
@if(session('success'))
    <div class="custom-alert-success" id="custom-alert-success">
        <span class="custom-alert-icon">✔</span> {{ session('success') }}
    </div>
@endif
</div>
<style>
  /* Style for the div by default */
  .product-info {
    background-color: aqua;
    border-radius: 10px;
    margin-left: 10px;
    padding: 10px;
    cursor: pointer; /* Indicates it's clickable */
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition */
    display: inline-block; /* To make sure it doesn't take full width if not needed */
    position: relative; /* Needed for positioning the "Delete" text or icon */
    overflow: hidden; /* To hide the "Delete" text initially if it's too long */
  }

  /* Style for the div on hover */
  .product-info:hover {
    background-color: red;
    color: white; /* Make text white for better contrast on red */
  }

  /* Add a "Delete" text or icon on hover */
  .product-info:hover::before {
    content: "Delete"; /* You can change this text */
    /* Alternatively, for an icon, you might use a unicode character or an icon font:
       content: "\f00d"; /* Example using Font Awesome trash icon (needs Font Awesome linked) */
       /* font-family: "Font Awesome 5 Free"; /* Example */
       /* font-weight: 900; /* Example */
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
  }

  .btn-primary {
    background-color:rgb(65, 201, 70); /* Green */
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
  }
  /* Hide the original text on hover if you only want to show "Delete" */
  .product-info:hover .original-content {
    visibility: hidden;
  }

  .form-flex {
    display: flex;
    gap: 40px;
}
.form-left, .form-right {
    flex: 1;
    min-width: 320px;
}
@media (max-width: 900px) {
    .form-flex { flex-direction: column; }
}

.product-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    max-width: 100%;
    overflow-x: auto;
    background: #f8f8f8;
    padding: 10px;
    border-radius: 8px;
}
.product-gallery img {
    max-width: 120px;
    height: auto;
    border-radius: 5px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    position: relative;
    transition: filter 0.3s, box-shadow 0.3s;
    cursor: pointer;
}

.product-gallery img:hover {
    filter: brightness(0.5) sepia(1) hue-rotate(-50deg) saturate(5) brightness(0.8);
    box-shadow: 0 0 0 4px red;
}

/* Hiển thị chữ "Delete" khi hover ảnh */
.product-gallery img::after {
    content: "";
    display: none;
}

.product-gallery img:hover::after {
    content: "Delete";
    color: #fff;
    font-weight: bold;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color:red;
    padding: 8px 18px;
    border-radius: 6px;
    font-size: 18px;
    display: block;
    pointer-events: none;
}

/* Đảm bảo ảnh nằm trong relative container để ::after định vị đúng */
.product-gallery {
    position: relative;
}
.product-gallery img {
    position: relative;
    z-index: 1;
}

/* Cập nhật topping: gói các checkbox trong 1 ô, tự xuống dòng */
.update-topping-checkboxes {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    background: #f8f8f8;
    padding: 10px;
    border-radius: 8px;
    max-width: 100%;
    margin-bottom: 10px;
}
.update-topping-checkboxes label {
    font-size: 17px;
    min-width: 180px;
    display: flex;
    align-items: center; /* Canh giữa theo chiều dọc */
    height: 40px;        /* (Tùy chọn) Chiều cao cố định cho label nếu muốn */
    margin-bottom: 0;
}

.custom-modal {
    position: fixed;
    z-index: 9999;
    left: 0; top: 0; width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.3);
    display: flex; align-items: center; justify-content: center;
}
.custom-modal-content {
    background: #fff;
    border-radius: 10px;
    padding: 32px 24px 24px 24px;
    min-width: 320px;
    max-width: 95vw;
    box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08), 0 1.5px 4px 0 rgba(0,0,0,0.03);
    position: relative;
}
.custom-modal-close {
    position: absolute;
    top: 12px; right: 18px;
    font-size: 2rem;
    color: #888;
    cursor: pointer;
    font-weight: bold;
    z-index: 2;
}
.custom-modal-close:hover { color: #e11d48; }

.custom-alert-success {
    background: #e6ffe6;
    color: #1a7f37;
    border: 1.5px solid #22c55e;
    border-radius: 8px;
    padding: 12px 24px;
    margin: 18px auto 18px auto;
    max-width: 420px;
    font-size: 1.08rem;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(34,197,94,0.08);
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
    animation: fadeInDown 0.5s;
    z-index: 10000;
}
.custom-alert-icon {
    font-size: 1.4em;
    color: #22c55e;
    margin-right: 8px;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<div class="content-wrapper">

						<!-- Row start -->
						<div class="row gutters">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

								<!-- Card start -->
								<div class="card">
									<div class="card-body">

										<!-- Row start -->
										<div class="row gutters">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-4 col-12">
												<div class="form-section-header">Account</div>
											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="input-group">
														<input class="form-control" type="text">
														<span class="input-group-text">
															<i class="icon-user1"></i>
														</span>
													</div>
													<div class="field-placeholder">Full Name <span class="text-danger">*</span></div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="input-group">
														<input class="form-control" type="email">
														<span class="input-group-text">
															<i class="icon-email"></i>
														</span>
													</div>
													<div class="field-placeholder">Email Address <span class="text-danger">*</span></div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="input-group">
														<input class="form-control" type="text">
														<span class="input-group-text">
															<i class="icon-phone1"></i>
														</span>
													</div>
													<div class="field-placeholder">Phone Number</div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-4 col-12">
												<div class="form-section-header">Billing <span class="title-info">We'll never share your with anyone.</span></div>
											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="input-group">
														<input class="form-control" type="text">
														<span class="input-group-text">
															<i class="icon-info2"></i>
														</span>
													</div>
													<div class="field-placeholder">Plan</div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="checkbox-container">
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="billingInterval" id="monthly" value="monthly">
															<label class="form-check-label" for="monthly">Monthly</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="billingInterval" id="quarterly" value="quarterly">
															<label class="form-check-label" for="quarterly">Quatrerly</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio" name="billingInterval" id="yearly" value="yearly" disabled="">
															<label class="form-check-label" for="yearly">Yearly</label>
														</div>
														<div class="field-placeholder">Billing Interval</div>
													</div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-4 col-12">
												<div class="form-section-header">Business Address</div>
											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="input-group">
														<input class="form-control" type="text">
														<span class="input-group-text">
															<i class="icon-map-pin"></i>
														</span>
													</div>
													<div class="field-placeholder">Street Address</div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="input-group">
														<input class="form-control" type="text">
														<span class="input-group-text">
															<i class="icon-map"></i>
														</span>
													</div>
													<div class="field-placeholder">City</div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="input-group">
														<input class="form-control" type="text">
														<span class="input-group-text">
															<i class="icon-edit-2"></i>
														</span>
													</div>
													<div class="field-placeholder">Postal Code</div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<textarea class="form-control" rows="2"></textarea>
													<div class="field-placeholder">Message <span class="text-danger">*</span></div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

												<!-- Field wrapper start -->
												<div class="field-wrapper">
													<div class="checkbox-container">
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" id="chcekEmail" value="option1">
															<label class="form-check-label" for="chcekEmail">Email</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" id="checkSms" value="option2">
															<label class="form-check-label" for="checkSms">SMS</label>
														</div>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" id="checkPhone" value="option3">
															<label class="form-check-label" for="checkPhone">Phone</label>
														</div>
														<div class="field-placeholder">Communication</div>
													</div>
												</div>
												<!-- Field wrapper end -->

											</div>
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
												<button class="btn btn-primary" disabled="">Submit</button>
											</div>
										</div>
										<!-- Row end -->

									</div>
								</div>
								<!-- Card end -->

							</div>
						</div>
						<!-- Row end -->

					</div>
  @include('footer')

  <script>
document.addEventListener('DOMContentLoaded', function() {
    // Xóa size
    document.querySelectorAll('.product-info:not(.topping-info)').forEach(function(div) {
        div.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa size này?')) {
                const sizeId = this.getAttribute('data-id');
                fetch(`/admin/size/delete/${sizeId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                       localStorage.setItem('successMessage', 'Xóa Size-Giá thành công!');
                       location.reload();
                    } else {
                        alert('Xóa Size-Giá thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });

    // Xóa topping
    document.querySelectorAll('.topping-info').forEach(function(span) {
        span.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa topping này?')) {
                const toppingId = this.getAttribute('data-id');
                fetch(`/admin/topping_detail/delete/${toppingId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                       localStorage.setItem('successMessage', 'Xóa Topping thành công!');
                       location.reload();
                    } else {
                        alert('Xóa Topping thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });

    // Xóa ảnh sản phẩm
    document.querySelectorAll('.product-gallery-img').forEach(function(img) {
        img.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
                const imgId = this.getAttribute('data-id');
                fetch(`/admin/product_img/delete/${imgId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                       localStorage.setItem('successMessage', 'Xóa Ảnh thành công!');
                       location.reload();
                    } else {
                        alert('Xóa Ảnh thất bại!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });

    const coverInput = document.getElementById('cover-image-input');
    const coverPreview = document.getElementById('cover-image-preview');
    coverPreview.addEventListener('click', function() {
        coverInput.click();
    });
    coverInput.addEventListener('change', function(e) {
        if (coverInput.files && coverInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreview.innerHTML = `<img src='${e.target.result}' style='width:100%;height:100%;object-fit:cover;border-radius:10px;'/>`;
            }
            reader.readAsDataURL(coverInput.files[0]);
        }
    });

    // Modal Size
    const sizeModal = document.getElementById('size-modal');
    const openSizeBtn = document.getElementById('open-size-modal');
    const closeSizeBtn = document.getElementById('close-size-modal');
    openSizeBtn && openSizeBtn.addEventListener('click', ()=>{ sizeModal.style.display = 'flex'; });
    closeSizeBtn && closeSizeBtn.addEventListener('click', ()=>{ sizeModal.style.display = 'none'; });
    window.addEventListener('click', function(e) {
        if (e.target === sizeModal) sizeModal.style.display = 'none';
    });
    // Modal Topping
    const toppingModal = document.getElementById('topping-modal');
    const openToppingBtn = document.getElementById('open-topping-modal');
    const closeToppingBtn = document.getElementById('close-topping-modal');
    openToppingBtn && openToppingBtn.addEventListener('click', ()=>{ toppingModal.style.display = 'flex'; });
    closeToppingBtn && closeToppingBtn.addEventListener('click', ()=>{ toppingModal.style.display = 'none'; });
    window.addEventListener('click', function(e) {
        if (e.target === toppingModal) toppingModal.style.display = 'none';
    });

     // Modal Image Gallery (Corrected)
    const imgGalleryModal = document.getElementById('img-modal'); // Correct ID
    const openImgGalleryBtn = document.getElementById('open-img-modal'); // Correct ID from the button
    const closeImgGalleryBtn = document.getElementById('close-img-modal'); // Correct ID

    openImgGalleryBtn && openImgGalleryBtn.addEventListener('click', ()=>{
        imgGalleryModal.style.display = 'flex';
    });
    closeImgGalleryBtn && closeImgGalleryBtn.addEventListener('click', ()=>{
        imgGalleryModal.style.display = 'none';
    });
    window.addEventListener('click', function(e) {
        if (e.target === imgGalleryModal) imgGalleryModal.style.display = 'none';
    })

    const alert = document.getElementById('custom-alert-success');
    if(alert) {
        setTimeout(()=>{ alert.style.display = 'none'; }, 2500);
    }

    // Hiển thị alert khi xóa thành công qua localStorage
    const msg = localStorage.getItem('successMessage');
    if (msg) {
        showCustomSuccess(msg);
        localStorage.removeItem('successMessage');
    }
});

function showCustomSuccess(msg) {
    let alert = document.createElement('div');
    alert.className = 'custom-alert-success';
    alert.innerHTML = '<span class="custom-alert-icon">✔</span> ' + msg;
    alert.id = 'custom-alert-success';
    var container = document.getElementById('alert-container');
    if(container) {
        container.appendChild(alert);
    } else {
        document.body.prepend(alert);
    }
    setTimeout(()=>{ alert.style.display = 'none'; }, 2500);
}
</script>
