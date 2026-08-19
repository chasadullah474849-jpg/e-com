<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    @include('admin.header')
  </head>

  <body>
    @include('admin.sidebar')
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <div class="layout-page">
          @include('admin.nav')

          <div class="container-fluid">
            <div class="card">
              <div class="card-header">
                <h4>Add Product</h4>
              </div>

              <div class="card-body">
                {{-- Crucial: Added enctype to allow file transfer --}}
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf

                  {{-- Product Name (Changed from select to standard input) --}}
                  <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Product Name" required>
                  </div>

                  {{-- Description --}}
                  <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                  </div>

                  {{-- Price --}}
                  <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                  </div>

                  {{-- Stock --}}
                  <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="0" required>
                  </div>

                  {{-- Category --}}
                  <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                      <option value="">Select Category</option>
                      @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  {{-- Sub Category --}}
                  <div class="mb-3">
                    <label class="form-label">Sub Category</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-control" required>
                      <option value="">Select Sub Category</option>
                      @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  {{-- Status --}}
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>

                  {{-- Multiple Product Images Upload Field --}}
                  <div class="mb-4">
    <label class="form-label">Product Images</label>

    <input
        type="file"
        id="images"
        name="images[]"
        class="form-control"
        multiple
        accept="image/jpeg,image/png,image/webp,image/jpg">

    <small class="text-muted">
        Hold Ctrl and click multiple images, then click Open.
    </small>

    <div class="row mt-3" id="preview"></div>
</div>

                  <button type="submit" class="btn btn-primary">Save Product</button>
                  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
                </form>
              </div>
            </div>
          </div>

          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <script>
const input = document.getElementById('images');
const preview = document.getElementById('preview');

input.addEventListener('change', function () {

    preview.innerHTML = "";

    [...this.files].forEach(file => {

        const reader = new FileReader();

        reader.onload = function(e){

            const col = document.createElement("div");
            col.className = "col-md-3 mb-3";

            col.innerHTML = `
                <img src="${e.target.result}"
                     class="img-fluid rounded border"
                     style="height:180px;width:100%;object-fit:cover;">
            `;

            preview.appendChild(col);

        };

        reader.readAsDataURL(file);

    });

});
</script>

          @include('admin.footer')
          @include('admin.js')
        </div>
      </div>
    </div>
  </body>
</html>
