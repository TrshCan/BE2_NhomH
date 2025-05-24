<?php
if (isset($_GET['from_date'])) {
    $fromDate = $_GET['from_date'];
}

if (isset($_GET['to_date'])) {
    $toDate = $_GET['to_date'];
}
?>
@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/styles/statistical.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Date picker library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
@endsection
@section('content')
<div class="container-s">
        <div class="header-s">
            <h1>Thống Kê Nâng Cao - Admin</h1>
        </div>

        <form id="filterForm" action="{{ route('admin.statistics.filter') }}" method="GET">
            <div class="filter-panel">
                <div class="filter-group">
                    <label>Từ Ngày</label>
                    <div class="date-input-container-s">
                        <input type="text" class="date-input flatpickr" name="from_date" value="{{ $fromDate ?? date('d/m/Y', strtotime('-30 days')) }}" id="fromDate" placeholder="DD/MM/YYYY">
                        <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                </div>

                <div class="filter-group">
                    <label>Đến Ngày</label>
                    <div class="date-input-container-s">
                        <input type="text" class="date-input flatpickr" name="to_date" value="{{ $toDate ?? date('d/m/Y') }}" id="toDate" placeholder="DD/MM/YYYY">
                        <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                </div>

                <div class="filter-group select-container-s">
                    <label>Danh Mục Sản Phẩm</label>
                    <select id="categorySelect" name="category_id" style="height: 40px;">
                        <option value="0" {{ $selectedCategory == 0 ? 'selected' : '' }}>Tất Cả</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ $selectedCategory == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="filter-button" id="filterButton">Lọc Dữ Liệu</button>
            </div>
        </form>

        <div class="stats-container-s">
            <div class="stats-card">
                <h3>Tổng Doanh Thu</h3>
                <p>{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
            </div>

            <div class="stats-card">
                <h3>Số Đơn Hàng</h3>
                <p>{{ $orderCount }}</p>
            </div>

            <div class="stats-card">
                <h3>Giá Trị Trung Bình</h3>
                <p>{{ $orderCount > 0 ? number_format($totalRevenue / $orderCount, 0, ',', '.') : 0 }}đ</p>
            </div>

            <div class="stats-card">
                <h3>Sản Phẩm Bán Chạy</h3>
                <p>{{ $topProduct ? $topProduct->product_name : 'Không có dữ liệu' }}</p>
            </div>
        </div>

        <div class="charts-container-s">
            <div class="chart-card">
                <h3>Doanh Thu Theo Thời Gian</h3>
                <div class="chart-container-s">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3>Doanh Thu Theo Danh Mục</h3>
                <div class="chart-container-s">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="charts-container-s">
            <div class="chart-card">
                <h3>Top 5 Sản Phẩm Bán Chạy</h3>
                <div class="chart-container-s">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <h3>Trạng Thái Đơn Hàng</h3>
                <div class="chart-container-s">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
    

@section('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr date pickers with Vietnamese locale
            flatpickr(".flatpickr", {
                dateFormat: "d/m/Y",
                locale: "vn",
                allowInput: true,
                disableMobile: "true"
            });

            // Format currency function
            function formatCurrency(value) {
                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
            }

            // Initialize monthly revenue chart
            const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
            const monthlyData = @json($monthlyRevenue);
            
            const monthlyRevenueChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Doanh Thu (đ)',
                        data: monthlyData.map(item => item.value),
                        borderColor: '#0066ff',
                        backgroundColor: 'rgba(0, 102, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.1,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatCurrency(context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });

            // Initialize category revenue chart
            const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
            const categoryData = @json($categoryRevenue);
            
            const categoryRevenueChart = new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: categoryData.map(item => item.category),
                    datasets: [{
                        label: 'Doanh Thu (đ)',
                        data: categoryData.map(item => item.value),
                        backgroundColor: generateColors(categoryData.length),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatCurrency(context.parsed.y);
                                }
                            }
                        }
                    }
                }
            });
            
            // Initialize top products chart
            const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
            const topProductsData = @json($topProducts);
            
            const topProductsChart = new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: topProductsData.map(item => item.product_name),
                    datasets: [{
                        label: 'Số Lượng Bán',
                        data: topProductsData.map(item => item.quantity_sold),
                        backgroundColor: '#4CAF50',
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
            
            // Initialize order status chart
            const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const orderStatusData = @json($orderStatusData);
            
            const orderStatusChart = new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: orderStatusData.map(item => item.status),
                    datasets: [{
                        data: orderStatusData.map(item => item.count),
                        backgroundColor: ['#2196F3', '#4CAF50', '#FFC107', '#FF5722', '#9C27B0'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });

            // Generate random colors for categories
            function generateColors(count) {
                const colors = [
                    '#0066ff', '#00bcd4', '#4CAF50', '#FFC107', '#FF5722',
                    '#9C27B0', '#673AB7', '#3F51B5', '#2196F3', '#009688'
                ];
                
                // If we need more colors than in our predefined array
                if (count > colors.length) {
                    for (let i = colors.length; i < count; i++) {
                        colors.push(getRandomColor());
                    }
                }
                
                return colors.slice(0, count);
            }
            
            function getRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        });
    </script>
@endsection
    
