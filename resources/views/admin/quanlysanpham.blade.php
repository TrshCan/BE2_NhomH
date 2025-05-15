@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Quản lý sản phẩm</h2>
        <button id="openAddModal" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Thêm sản phẩm
        </button>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-md overflow-x-auto animate-slide-in">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">is_featured</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($products as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->product_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->product_name }}">{{ $item->product_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->description }}">{{ $item->description }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ number_format($item->price, 0, ',', '.') }} VND</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->is_featured ? 'Có' : 'Không' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->stock_quantity }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->category_id }}">{{ $item->category_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate" title="{{ $item->brand_id }}">{{ $item->brand_id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->product_name }}" class="h-10 w-10 rounded object-cover" >
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $item->sales_count }}</td>
                        <td class="px-6 py-4 text-sm">
                            <button class="text-teal-500 hover:text-teal-600 mr-4 edit-product" data-id="{{ $item->product_id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-500 hover:text-red-600 delete-product" data-id="{{ $item->product_id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500">Không có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Add/Edit Product -->
<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-4">Thêm sản phẩm</h3>
        <form id="productForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tên sản phẩm</label>
                <input type="text" name="product_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Giá</label>
                <input type="number" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">is_featured</label>
                <select name="is_featured" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                    <option value="0">Không</option>
                    <option value="1">Có</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tồn kho</label>
                <input type="number" name="stock_quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Danh mục (ID)</label>
                <input type="text" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Brand (ID)</label>
                <input type="text" name="brand_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Hình ảnh (URL)</label>
                <input type="text" name="image_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Số lượng bán</label>
                <input type="number" name="sales_count" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">Hủy</button>
                <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-200">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
    const openAddModal = document.getElementById('openAddModal');
    const productModal = document.getElementById('productModal');
    const closeModal = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const productForm = document.getElementById('productForm');
    const editButtons = document.querySelectorAll('.edit-product');

    // Open modal for adding product
    openAddModal.addEventListener('click', () => {
        modalTitle.textContent = 'Thêm sản phẩm';
        productForm.reset();
        productModal.classList.remove('hidden');
    });

    // Open modal for editing product (placeholder)
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            modalTitle.textContent = 'Chỉnh sửa sản phẩm';
            // Placeholder: Populate form with product data based on data-id
            productModal.classList.remove('hidden');
        });
    });

    // Close modal
    closeModal.addEventListener('click', () => {
        productModal.classList.add('hidden');
    });

    // Close modal on outside click
    productModal.addEventListener('click', (e) => {
        if (e.target === productModal) {
            productModal.classList.add('hidden');
        }
    });
</script>
@endsection