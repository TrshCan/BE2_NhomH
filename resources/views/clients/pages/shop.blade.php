@extends('layouts.client')

@section('title', 'Shop - TechGear Shop')

@section('content')
  <main class="container product_section_container mt-5 pt-5">
    <div class="row product_section">
      <!-- Breadcrumbs -->
      <nav aria-label="breadcrumb" class="breadcrumbs mb-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
        </ol>
      </nav>

      <!-- Sidebar -->
      <aside class="col-lg-3 sidebar">
        <div class="sidebar_section mb-4 filter-card">
          <h5 class="sidebar_title">Danh mục Sản phẩm</h5>
          <ul class="checkboxes list-unstyled category-list">
            @foreach ($categories as $category)
                        <li>
                            <label>
                                <input type="checkbox"
                                       class="custom-checkbox"
                                       data-filter="category-{{ $category->category_id }}">
                                {{ $category->category_name }}
                            </label>
                        </li>
                    @endforeach
          </ul>
          <button class="show_more btn btn-outline-primary btn-sm mt-2" data-target="category-list">
            <i class="fas fa-plus me-1"></i> Xem thêm
          </button>
        </div>
        <div class="sidebar_section mb-4 filter-card">
          <h5 class="sidebar_title">Mức giá</h5>
          <ul class="checkboxes list-unstyled price-list">
            <li>
              <input type="checkbox" id="price1" class="custom-checkbox">
              <label for="price1" class="ms-2">0đ - 2,500,000đ</label>
            </li>
            <li>
              <input type="checkbox" id="price2" class="custom-checkbox">
              <label for="price2" class="ms-2">2,500,000đ - 12,500,000đ</label>
            </li>
            <li>
              <input type="checkbox" id="price3" class="custom-checkbox">
              <label for="price3" class="ms-2">12,500,000đ - 25,000,000đ</label>
            </li>
            <li class="hidden-item">
              <input type="checkbox" id="price4" class="custom-checkbox">
              <label for="price4" class="ms-2">25,000,000đ - 37,500,000đ</label>
            </li>
          </ul>
          <button class="show_more btn btn-outline-primary btn-sm mt-2" data-target="price-list">
            <i class="fas fa-plus me-1"></i> Xem thêm
          </button>
          <div class="price-range-filter mt-3">
            <label for="price-min">Price Range:</label>
            <div class="d-flex align-items-center mb-2">
              <input type="number" id="price-min" class="form-control me-2" placeholder="Tối thiểu" min="0" step="10000">
              <span>-</span>
              <input type="number" id="price-max" class="form-control ms-2" placeholder="Tối đa" min="0" step="10000">
            </div>
            <button class="filter_button btn w-100"><span>Lọc</span></button>
          </div>
        </div>
        
        <div class="sidebar_section filter-card">
          <h5 class="sidebar_title">Brand</h5>
          <ul class="checkboxes list-unstyled brand-list">
            @foreach ($brands as $brand)
    <div class="form-check">
        <input class="form-check-input custom-checkbox"
               type="checkbox"
               value=""
               id="brand-{{ $brand->id }}"
               data-filter="brand-{{ $brand->id }}">
        <label class="form-check-label" for="brand-{{ $brand->id }}">
            {{ $brand->name }}
        </label>
    </div>
@endforeach

          </ul>
          <button class="show_more btn btn-outline-primary btn-sm mt-2" data-target="brand-list">
            <i class="fas fa-plus me-1"></i> Xem thêm
          </button>
        </div>
      </aside>

      <!-- Main Content -->
      <section class="col-lg-9 main_content">
        <div class="products_iso">
          <!-- Product Sorting -->
          <div class="product_sorting_container product_sorting_container_top d-flex justify-content-between align-items-center mb-4">
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="type_sorting_text">Lọc</span>
              </button>
              <ul class="dropdown-menu sorting_type">
                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "original-order" }'><a class="dropdown-item" href="#">Tất Cả</a></li>
                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "price" }'><a class="dropdown-item" href="#">Giá</a></li>
                <li class="type_sorting_btn" data-isotope-option='{ "sortBy": "name" }'><a class="dropdown-item" href="#">Tên Sản phẩm</a></li>
              </ul>
            </div>
            <div class="d-flex align-items-center">
              <div class="dropdown me-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Show <span class="num_sorting_text">8</span>
                </button>
                <ul class="dropdown-menu sorting_num">
                  <li><a class="dropdown-item num_sorting_btn" href="#">6</a></li>
                  <li><a class="dropdown-item num_sorting_btn" href="#">12</a></li>
                  <li><a class="dropdown-item num_sorting_btn" href="#">24</a></li>
                </ul>
              </div>
            </div>
          </div>
<!-- Product Grid -->
 <div class="product-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                            @foreach ($products as $item2)
                                 <div class="col product-item category-{{ $item2->category_id }} brand-{{ $item2->brand_id }}">
                                <div class="card product discount product_filter h-100">
                                    <div class="product_image">
                               <img src="{{ asset('assets/images/'.$item2->image_url) }}" class="card-img-top" alt="{{ $item2->product_name }}">

                                    </div>
                                    <div class="favorite favorite_left"></div>
                                    <div class="product_bubble product_bubble_right product_bubble_red">
                                        <span>-{{ number_format(10 * 25000, 0, ',', '.') }}đ</span>
                                    </div>
                                    <div class="card-body product_info text-center">
                                        <h6 class="card-title product_name"><a href="{{ route('products.show', $item2->product_id) }}">{{$item2->product_name}}</a></h6>
                    <div class="product_price">{{ number_format($item2->price * 25000, 0, ',', '.') }}đ <span></span></div>
                                    </div>
                                    <div class="card-footer add_to_cart_button">
                                        <a href="#" class="btn red_button w-100">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                           
                           
                            
                      
                        </div>
         

          <!-- Product Sorting Bottom -->
          <div class="product_sorting_container product_sorting_container_bottom d-flex justify-content-between align-items-center mt-4">
            
            <div class="d-flex align-items-center">
              <div class="dropdown me-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Show: <span class="num_sorting_text">04</span>
                </button>
                <ul class="dropdown-menu sorting_num">
                  <li><a class="dropdown-item num_sorting_btn" href="#">01</a></li>
                  <li><a class="dropdown-item num_sorting_btn" href="#">02</a></li>
                  <li><a class="dropdown-item num_sorting_btn" href="#">03</a></li>
                  <li><a class="dropdown-item num_sorting_btn" href="#">04</a></li>
                </ul>
              </div>
              <nav aria-label="Page navigation" class="pages">
               {{ $products->links('pagination::bootstrap-5') }}
              </nav>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
@endsection