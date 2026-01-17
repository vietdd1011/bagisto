@extends('admin::layouts.content')

@section('page_title')
Nhập hàng mới
@stop

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .main-container {
            padding: 20px;
        }

        .page-header-custom {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e8e8e8;
        }

        .page-header-custom h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
            font-weight: 600;
        }

        .card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px;
            border-bottom: 1px solid #e8e8e8;
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .product-card {
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
        }

        .product-card:hover {
            border-color: #0041ff;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-number {
            font-weight: 600;
            color: #667eea;
            font-size: 16px;
        }

        .product-number {
            font-weight: 600;
            color: #3a3a3a;
            font-size: 14px;
        }

        .btn-remove {
            color: #e74c3c;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-remove:hover {
            text-decoration: underline;
            color: #c0392b;
        }

        .form-label {
            font-weight: 600;
            color: #3a3a3a;
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            border: 1px solid #c7c7c7;
            border-radius: 2px;
            padding: 8px 12px;
            font-size: 14px;
            height: 38px;
        }

        .form-control:focus {
            outline: none;
            border-color: #0041ff;
        }

        .card-body {
            padding: 20px;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-light {
            background: white;
            color: #000;
            border: 1px solid #c7c7c7;
        }

        .btn-light:hover {
            background: #f8f9fa;
        }

        .btn-custom {
            padding: 10px 20px;
        }

        .btn-primary-custom {
            background: #0041ff;
            color: white;
        }

        .btn-primary-custom:hover {
            background: #0031d4;
        }

        .btn-success-custom {
            background: white;
            color: #0041ff;
            border: 1px solid #0041ff;
        }

        .btn-success-custom:hover {
            background: #0041ff;
            color: white;
        }

        /* Autocomplete */
        .autocomplete-wrapper {
            position: relative;
        }

        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #c7c7c7;
            border-top: none;
            border-radius: 0 0 2px 2px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .autocomplete-results.active {
            display: block;
        }

        .autocomplete-item {
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.1s;
        }

        .autocomplete-item:hover {
            background: #f8f9fa;
        }

        .required-star {
            color: #e74c3c;
        }

        .badge-custom {
            background: #e8e8e8;
            color: #333;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
        }

        /* Utility Classes */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -10px;
            margin-right: -10px;
        }

        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding-left: 10px;
            padding-right: 10px;
            margin-bottom: 15px;
        }

        @media (max-width: 992px) {
            .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .gap-2 { gap: 10px; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        .mb-0 { margin-bottom: 0; }
        .me-1, .me-2 { margin-right: 8px; }
        .flex-grow-1 { flex-grow: 1; }
        .opacity-75 { opacity: 0.75; }
        .g-3 > * { padding: 10px; }
    </style>
@endpush

@section('content')
<div class="main-container">
    <!-- Header -->
    <div class="page-header-custom">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Tạo Phiếu Nhập Hàng Mới</h1>
            <button type="button" onclick="history.back()" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.stock_documents.store') }}" id="stockForm">
        @csrf

        <!-- Thông tin phiếu nhập -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-file-alt me-2"></i> Thông Tin Phiếu Nhập
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Mã Phiếu Nhập <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="document_number" placeholder="VD: PN-2026-001"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Người Nhập <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="created_by" placeholder="Tên người tạo phiếu"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ghi Chú</label>
                        <input type="text" class="form-control" name="note" placeholder="Ghi chú (không bắt buộc)">
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-shopping-cart me-2"></i> Danh Sách Sản Phẩm</span>
                <span class="badge bg-primary" id="productCount">1 sản phẩm</span>
            </div>
            <div class="card-body">
                <div id="products-list">
                    <!-- Product 1 -->
                    <div class="product-card" data-index="0">
                        <div class="product-header">
                            <span class="product-number"><i class="fas fa-cube me-2"></i>Sản phẩm #1</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="autocomplete-wrapper">
                                    <label class="form-label">Số Hiệu SP <span class="required-star">*</span></label>
                                    <input type="text" class="form-control product-number-input"
                                        name="products[0][product_number]" placeholder="VD: SP-001" autocomplete="off"
                                        data-index="0" required>
                                    <div class="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control sku-input" name="products[0][sku]"
                                    placeholder="Mã SKU" data-index="0">
                            </div>
                            <div class="col-md-4">
                                <div class="autocomplete-wrapper">
                                    <label class="form-label">Màu Sắc <span class="required-star">*</span></label>
                                    <input type="text" class="form-control color-input" name="products[0][color]"
                                        placeholder="VD: Đỏ" autocomplete="off" data-index="0" required>
                                    <div class="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="autocomplete-wrapper">
                                    <label class="form-label">Kích Cỡ <span class="required-star">*</span></label>
                                    <input type="text" class="form-control size-input" name="products[0][size]"
                                        placeholder="VD: M" autocomplete="off" data-index="0" required>
                                    <div class="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giá (VNĐ) <span class="required-star">*</span></label>
                                <input type="number" class="form-control price-input" name="products[0][price]"
                                    placeholder="0" min="0" data-index="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số Lượng <span class="required-star">*</span></label>
                                <input type="number" class="form-control" name="products[0][quantity]" placeholder="0"
                                    min="1" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-success-custom btn-custom" onclick="addProduct()">
                        <i class="fas fa-plus me-2"></i> Thêm Sản Phẩm
                    </button>
                    <button type="submit" class="btn btn-primary-custom btn-custom flex-grow-1">
                        <i class="fas fa-save me-2"></i> Lưu Phiếu Nhập
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let productIndex = 1;
        let autocompleteTimeout = null;

        function addProduct() {
            const productNumber = productIndex + 1;

            const html = `
                    <div class="product-card" data-index="${productIndex}">
                        <div class="product-header">
                            <span class="product-number"><i class="fas fa-cube me-2"></i>Sản phẩm #${productNumber}</span>
                            <span class="btn-remove" onclick="removeProduct(this)">
                                <i class="fas fa-trash-alt me-1"></i> Xóa
                            </span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="autocomplete-wrapper">
                                    <label class="form-label">Số Hiệu SP <span class="required-star">*</span></label>
                                    <input type="text" class="form-control product-number-input" 
                                           name="products[${productIndex}][product_number]" 
                                           placeholder="VD: SP-001" 
                                           autocomplete="off" 
                                           data-index="${productIndex}" required>
                                    <div class="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control sku-input" 
                                       name="products[${productIndex}][sku]" 
                                       placeholder="Mã SKU" 
                                       data-index="${productIndex}">
                            </div>
                            <div class="col-md-4">
                                <div class="autocomplete-wrapper">
                                    <label class="form-label">Màu Sắc <span class="required-star">*</span></label>
                                    <input type="text" class="form-control color-input" 
                                           name="products[${productIndex}][color]" 
                                           placeholder="VD: Đỏ" 
                                           autocomplete="off" 
                                           data-index="${productIndex}" required>
                                    <div class="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="autocomplete-wrapper">
                                    <label class="form-label">Kích Cỡ <span class="required-star">*</span></label>
                                    <input type="text" class="form-control size-input" 
                                           name="products[${productIndex}][size]" 
                                           placeholder="VD: M" 
                                           autocomplete="off" 
                                           data-index="${productIndex}" required>
                                    <div class="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giá (VNĐ) <span class="required-star">*</span></label>
                                <input type="number" class="form-control price-input" 
                                       name="products[${productIndex}][price]" 
                                       placeholder="0" 
                                       min="0" 
                                       data-index="${productIndex}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số Lượng <span class="required-star">*</span></label>
                                <input type="number" class="form-control" 
                                       name="products[${productIndex}][quantity]" 
                                       placeholder="0" 
                                       min="1" required>
                            </div>
                        </div>
                    </div>
                `;

            document.getElementById('products-list').insertAdjacentHTML('beforeend', html);
            productIndex++;
            updateProductCount();
            attachEventListeners();
        }

        function removeProduct(element) {
            element.closest('.product-card').remove();
            updateProductCount();
            renumberProducts();
        }

        function updateProductCount() {
            const count = document.querySelectorAll('.product-card').length;
            document.getElementById('productCount').textContent = `${count} sản phẩm`;
        }

        function renumberProducts() {
            const products = document.querySelectorAll('.product-card');
            products.forEach((product, index) => {
                const numberSpan = product.querySelector('.product-number');
                if (numberSpan) {
                    numberSpan.innerHTML = `<i class="fas fa-cube me-2"></i>Sản phẩm #${index + 1}`;
                }
            });
        }

        // Autocomplete for product number
        function setupProductNumberAutocomplete(input) {
            input.addEventListener('input', function () {
                const query = this.value;
                const resultsDiv = this.nextElementSibling;

                if (query.length < 2) {
                    resultsDiv.classList.remove('active');
                    return;
                }

                clearTimeout(autocompleteTimeout);
                autocompleteTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.stock_documents.search_products') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(product => {
                                    const item = document.createElement('div');
                                    item.className = 'autocomplete-item';
                                    item.textContent = `${product.sku} (ID: ${product.id})`;
                                    item.onclick = () => {
                                        input.value = product.id;
                                        resultsDiv.classList.remove('active');
                                    };
                                    resultsDiv.appendChild(item);
                                });
                                resultsDiv.classList.add('active');
                            } else {
                                resultsDiv.classList.remove('active');
                            }
                        });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target)) {
                    input.nextElementSibling.classList.remove('active');
                }
            });
        }

        // Auto-fill on SKU input
        function setupSkuAutofill(input) {
            input.addEventListener('blur', function () {
                const sku = this.value.trim();
                if (!sku) return;

                const row = this.closest('.product-card');

                fetch(`{{ route('admin.stock_documents.get_product_by_sku') }}?sku=${encodeURIComponent(sku)}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Product not found');
                        return response.json();
                    })
                    .then(data => {
                        const productNumberInput = row.querySelector('.product-number-input');
                        if (productNumberInput && !productNumberInput.value) {
                            productNumberInput.value = data.id;
                        }

                        const priceInput = row.querySelector('.price-input');
                        if (priceInput && !priceInput.value && data.price) {
                            priceInput.value = data.price;
                        }
                    })
                    .catch(error => {
                        console.log('Product not found');
                    });
            });
        }

        // Autocomplete for colors
        function setupColorAutocomplete(input) {
            input.addEventListener('input', function () {
                const query = this.value;
                const resultsDiv = this.nextElementSibling;

                if (query.length < 1) {
                    resultsDiv.classList.remove('active');
                    return;
                }

                clearTimeout(autocompleteTimeout);
                autocompleteTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.stock_documents.get_colors') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(color => {
                                    const item = document.createElement('div');
                                    item.className = 'autocomplete-item';
                                    item.textContent = color;
                                    item.onclick = () => {
                                        input.value = color;
                                        resultsDiv.classList.remove('active');
                                    };
                                    resultsDiv.appendChild(item);
                                });
                                resultsDiv.classList.add('active');
                            } else {
                                resultsDiv.classList.remove('active');
                            }
                        });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target)) {
                    input.nextElementSibling.classList.remove('active');
                }
            });
        }

        // Autocomplete for sizes
        function setupSizeAutocomplete(input) {
            input.addEventListener('input', function () {
                const query = this.value;
                const resultsDiv = this.nextElementSibling;

                if (query.length < 1) {
                    resultsDiv.classList.remove('active');
                    return;
                }

                clearTimeout(autocompleteTimeout);
                autocompleteTimeout = setTimeout(() => {
                    fetch(`{{ route('admin.stock_documents.get_sizes') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(size => {
                                    const item = document.createElement('div');
                                    item.className = 'autocomplete-item';
                                    item.textContent = size;
                                    item.onclick = () => {
                                        input.value = size;
                                        resultsDiv.classList.remove('active');
                                    };
                                    resultsDiv.appendChild(item);
                                });
                                resultsDiv.classList.add('active');
                            } else {
                                resultsDiv.classList.remove('active');
                            }
                        });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target)) {
                    input.nextElementSibling.classList.remove('active');
                }
            });
        }

        function attachEventListeners() {
            document.querySelectorAll('.product-number-input').forEach(input => {
                setupProductNumberAutocomplete(input);
            });

            document.querySelectorAll('.sku-input').forEach(input => {
                setupSkuAutofill(input);
            });

            document.querySelectorAll('.color-input').forEach(input => {
                setupColorAutocomplete(input);
            });

            document.querySelectorAll('.size-input').forEach(input => {
                setupSizeAutocomplete(input);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            attachEventListeners();
        });
    </script>
@endpush