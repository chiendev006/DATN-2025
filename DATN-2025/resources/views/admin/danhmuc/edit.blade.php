@include('header')
<section class="is-hero-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <h1 class="title">
Chỉnh sửa danh mục    </h1>
  </div>
</section>
 <section class="section main-section">
    <div class="card mb-6 col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">

      <div style=" margin: 0 auto;" class="card-content col-xl-11 col-lg-11 col-md-11 col-sm-11 col-11" >
        <form action="{{ route('danhmuc.update',['id'=>$danhmuc->id]) }}" method="post">
            @csrf
        <div class="field-wrapper ">
            <div class="field-placeholder">Size - Giá</div>
            <div class="field-body">
              <div class="field">
                <div class="control icons-left">
                  <input class="input" type="text" value="{{ $danhmuc['name'] }}" name="name" placeholder="Name">
                  <span class="icon left"><i class="mdi mdi-account"></i></span>
                  @error('name')
                    <p style="color: red;">Bạn chưa nhập tên danh mục !!!</p>
                  @enderror
                </div>
           </div>
        </div>
        </div>
    <br>
            <div class="field-wrapper">
                 <div class="field-placeholder">Có topping?</div>
           <br>
            <div class="control" style="margin-top: 8px;">
                	<div class="form-check form-check-inline">
						<input   {{ $danhmuc->has_topping == 1 ? 'checked' : '' }}  class="form-check-input" type="radio" name="has_topping" id="inlineRadio1" value="1">
						<label class="form-check-label" for="inlineRadio1">Có</label>
					</div>
					<div class="form-check form-check-inline">
						<input   {{ $danhmuc->has_topping == 0 ? 'checked' : '' }}  class="form-check-input" type="radio" name="has_topping" id="inlineRadio2" value="0">
					    <label class="form-check-label" for="inlineRadio2">Không</label>
                    </div>


              </div>
          </div>

          <div class="field grouped">
            <div  class="control">
              <button  type="submit" class="btn-success" >Cập nhật</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
  <style>
     .btn-success {
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
  .btn-success:hover {
    background-color: rgb(0, 0, 217);
  }
  </style>
  @include('footer')
