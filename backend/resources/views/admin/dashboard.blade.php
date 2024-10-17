@extends('Layout.Layout')

@section('tiltle')
    DashBoard Admin
@endsection

@section('content_admin')
    <div class="container text-center mt-5 mb-3">
        <h2>DashBoard Admin</h2>
    </div>

    <div class="row container mt-3 mb-3">
        <!-- Earnings (Daily) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="background-color: #e7f3ff;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Lượt truy cập website hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark">1,764</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="background-color: #d4edda;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Lượt truy cập website tháng này
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark">76,434</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- New Users Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="background-color: #d1ecf1;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Người dùng mới trong tháng này
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-dark">12</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Total Users Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="background-color:#fff3cd;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tổng người dùng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark">1898</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
 
    <div class="row container mt-3 mb-3">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #f8f9fa;">
                    <h6 class="m-0 font-weight-bold text-primary">Tổng quan về thu nhập</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #f8f9fa;">
                    <h6 class="m-0 font-weight-bold text-primary">Nguồn thu nhập</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Chuyển khoản
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Thanh toán khi nhận hàng
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Khác
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
