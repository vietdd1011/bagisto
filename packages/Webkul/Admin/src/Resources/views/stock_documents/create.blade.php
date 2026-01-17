@extends('admin::layouts.content')

@section('page_title')
    Nhập hàng mới
@endsection

@push('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .stock-document-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .page-header h1 {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .page-header-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group-modern {
            margin-bottom: 0;
        }

        .form-group-modern label {
            display: block;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control-modern {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control-modern:hover {
            border-color: #cbd5e0;
        }

        .product-item {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            border: 2px solid #e6edff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.3s ease;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
        }

        .product-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e6edff;
        }

        .product-number-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .remove-product-btn {
            background: #fc8181;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remove-product-btn:hover {
            background: #f56565;
            transform: scale(1.05);
        }

        .product-fields {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-modern:active {
            transform: translateY(0);
        }

        .btn-add {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
            justify-content: center;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6b3fa0 100%);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #a0aec0;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .stock-document-container {
                padding: 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .product-fields {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-modern {
                width: 100%;
                justify-content: center;
            }
        }

        .input-icon {
            position: relative;
        }

        .input-icon::before {
            content: attr(data-icon);
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.25rem;
        }

        .summary-card {
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
            color: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="stock-document-container">
        <div class="page-header">
            <h1>
                <div class="page-header-icon">📦</div>
                Tạo phiếu nhập hàng mới
            </h1>
        </div>

        <form method="POST" action="{{ route('admin.stock_documents.store') }}" id="stockForm">
            @csrf

            <!-- Document Information Card -->
            <div class="form-card">
                <div class="card-title">
                    <div class="card-icon">📋</div>
                    Thông tin phiếu nhập
                </div>
                <div class="form-grid">
                    <div class="form-group-modern">
                        <label for="document_number">Mã phiếu nhập *</label>
                        <input type="text" 
                               name="document_number" 
                               id="document_number"
                               class="form-control-modern" 
                               placeholder="Nhập mã phiếu (VD: PN-2026-001)"
                               required>
                    </div>
                    <div class="form-group-modern">
                        <label for="created_by">Người nhập *</label>
                        <input type="text" 
                               name="created_by" 
                               id="created_by"
                               class="form-control-modern" 
                               placeholder="Tên người tạo phiếu"
                               required>
                    </div>
                    <div class="form-group-modern" style="grid-column: 1 / -1;">
                        <label for="note">Ghi chú</label>
                        <input type="text" 
                               name="note" 
                               id="note"
                               class="form-control-modern" 
                               placeholder="Thêm ghi chú cho phiếu nhập (không bắt buộc)">
                    </div>
                </div>
            </div>

            <!-- Products List Card -->
            <div class="form-card">
                <div class="card-title">
                    <div class="card-icon">🛍️</div>
                    Danh sách sản phẩm nhập
                    <span id="productCount" style="margin-left: auto; background: #e6edff; color: #667eea; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem;">1 sản phẩm</span>
                </div>

                <div id="products-list">
                    <div class="product-item" data-index="0">
                        <div class="product-item-header">
                            <div class="product-number-badge">Sản phẩm #1</div>
                        </div>
                        <div class="product-fields">
                            <div class="form-group-modern">
                                <label>Số hiệu sản phẩm *</label>
                                <input type="text" 
                                       name="products[0][product_number]" 
                                       class="form-control-modern" 
                                       placeholder="VD: SP-001"
                                       required>
                            </div>
                            <div class="form-group-modern">
                                <label>SKU</label>
                                <input type="text" 
                                       name="products[0][sku]" 
                                       class="form-control-modern" 
                                       placeholder="Mã SKU">
                            </div>
                            <div class="form-group-modern">
                                <label>Màu sắc *</label>
                                <input type="text" 
                                       name="products[0][color]" 
                                       class="form-control-modern" 
                                       placeholder="VD: Đỏ, Xanh"
                                       required>
                            </div>
                            <div class="form-group-modern">
                                <label>Kích cỡ *</label>
                                <input type="text" 
                                       name="products[0][size]" 
                                       class="form-control-modern" 
                                       placeholder="VD: M, L, XL"
                                       required>
                            </div>
                            <div class="form-group-modern">
                                <label>Giá (VNĐ) *</label>
                                <input type="number" 
                                       name="products[0][price]" 
                                       class="form-control-modern product-price" 
                                       placeholder="0"
                                       min="0"
                                       required>
                            </div>
                            <div class="form-group-modern">
                                <label>Số lượng *</label>
                                <input type="number" 
                                       name="products[0][quantity]" 
                                       class="form-control-modern product-quantity" 
                                       placeholder="0"
                                       min="1"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" onclick="addProduct()" class="btn-modern btn-add">
                        <span>➕</span>
                        Thêm sản phẩm mới
                    </button>
                    <button type="submit" class="btn-modern btn-submit">
                        <span>💾</span>
                        Lưu phiếu nhập hàng
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let productIndex = 1;

        function addProduct() {
            const productsList = document.getElementById('products-list');
            const productNumber = productIndex + 1;

            const productItem = document.createElement('div');
            productItem.className = 'product-item';
            productItem.setAttribute('data-index', productIndex);

            productItem.innerHTML = `
                <div class="product-item-header">
                    <div class="product-number-badge">Sản phẩm #${productNumber}</div>
                    <button type="button" class="remove-product-btn" onclick="removeProduct(this)">
                        <span>🗑️</span>
                        Xóa
                    </button>
                </div>
                <div class="product-fields">
                    <div class="form-group-modern">
                        <label>Số hiệu sản phẩm *</label>
                        <input type="text" 
                               name="products[${productIndex}][product_number]" 
                               class="form-control-modern" 
                               placeholder="VD: SP-001"
                               required>
                    </div>
                    <div class="form-group-modern">
                        <label>SKU</label>
                        <input type="text" 
                               name="products[${productIndex}][sku]" 
                               class="form-control-modern" 
                               placeholder="Mã SKU">
                    </div>
                    <div class="form-group-modern">
                        <label>Màu sắc *</label>
                        <input type="text" 
                               name="products[${productIndex}][color]" 
                               class="form-control-modern" 
                               placeholder="VD: Đỏ, Xanh"
                               required>
                    </div>
                    <div class="form-group-modern">
                        <label>Kích cỡ *</label>
                        <input type="text" 
                               name="products[${productIndex}][size]" 
                               class="form-control-modern" 
                               placeholder="VD: M, L, XL"
                               required>
                    </div>
                    <div class="form-group-modern">
                        <label>Giá (VNĐ) *</label>
                        <input type="number" 
                               name="products[${productIndex}][price]" 
                               class="form-control-modern product-price" 
                               placeholder="0"
                               min="0"
                               required>
                    </div>
                    <div class="form-group-modern">
                        <label>Số lượng *</label>
                        <input type="number" 
                               name="products[${productIndex}][quantity]" 
                               class="form-control-modern product-quantity" 
                               placeholder="0"
                               min="1"
                               required>
                    </div>
                </div>
            `;

            productsList.appendChild(productItem);
            productIndex++;
            updateProductCount();

            // Smooth scroll to new product
            setTimeout(() => {
                productItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }

        function removeProduct(button) {
            const productItem = button.closest('.product-item');
            productItem.style.animation = 'slideOut 0.3s ease';

            setTimeout(() => {
                productItem.remove();
                updateProductCount();
                renumberProducts();
            }, 300);
        }

        function updateProductCount() {
            const count = document.querySelectorAll('.product-item').length;
            const countElement = document.getElementById('productCount');
            countElement.textContent = `${count} sản phẩm`;
        }

        function renumberProducts() {
            const products = document.querySelectorAll('.product-item');
            products.forEach((product, index) => {
                const badge = product.querySelector('.product-number-badge');
                badge.textContent = `Sản phẩm #${index + 1}`;
            });
        }

        // Add slideOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOut {
                to {
                    opacity: 0;
                    transform: translateX(-100%);
                }
            }
        `;
        document.head.appendChild(style);

        // Form validation feedback
        document.getElementById('stockForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#fc8181';
                    field.style.animation = 'shake 0.5s';
                } else {
                    field.style.borderColor = '#48bb78';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('⚠️ Vui lòng điền đầy đủ các trường bắt buộc (*)');
            }
        });

        // Add shake animation for validation
        const shakeStyle = document.createElement('style');
        shakeStyle.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-10px); }
                75% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(shakeStyle);
    </script>
@endsection
