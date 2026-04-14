@extends('layouts.app')
@section('title', 'Home')


@section('content')
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Home</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="utama.html">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page">Home</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
        @if(auth()->user()->isAdmin())
        <div class="card mt-4">
          <div class="card-body">
            <h5>Report Kasir</h5>

            <a href="/report" class="btn btn-primary">Report Harian Penjualan</a>

            <a href="/report/setoran" class="btn btn-success">
                Report Setoran Kasir
            </a>
          </div>
        </div>
        @endif

        <!-- [ sample-page ] start -->
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 text-muted">Total Produk</h6>
              <h4>{{ $totalProducts }}</h4>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 text-muted">Total Transaksi</h6>
              <h4>{{ $totalTransactions }}</h4>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 text-muted">Pendapatan Hari Ini</h6>
              <h4>Rp {{ number_format($todayIncome,0,',','.') }}</h4>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 text-muted">Total Vendor</h6>
              <h4>{{ $totalVendors }}</h4>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-xl-8">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">Unique Visitor</h5>
            <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="chart-tab-home-tab" data-bs-toggle="pill" data-bs-target="#chart-tab-home" type="button" role="tab" aria-controls="chart-tab-home" aria-selected="true">Month</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="chart-tab-profile-tab" data-bs-toggle="pill" data-bs-target="#chart-tab-profile" type="button" role="tab" aria-controls="chart-tab-profile" aria-selected="false">Week</button>
              </li>
            </ul>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="tab-content" id="chart-tab-tabContent">
                <div class="tab-pane" id="chart-tab-home" role="tabpanel" aria-labelledby="chart-tab-home-tab" tabindex="0">
                  <div id="visitor-chart-1"></div>
                </div>
                <div class="tab-pane show active" id="chart-tab-profile" role="tabpanel">
                  <div class="card">
                    <div class="card-body">
                      <h5>Grafik Penjualan 7 Hari</h5>
                      <canvas id="salesChart"></canvas>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>

      <div class="row">

        <div class="col-md-6">
          <h5 class="mb-3">Produk Terlaris</h5>
          <div class="card">
            <div class="card-body">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Ranking</th>
                    <th>Produk</th>
                    <th>Jumlah Terjual</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach($topProducts as $index => $product)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->total_sold }} barang</td>
                  </tr>
                  @endforeach
                </tbody>

              </table>
            </div>
          </div>
        </div>


        <div class="col-md-6">
          <h5 class="mb-3">Produk Stok Hampir Habis</h5>
          <div class="card">
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Produk</th>
                    <th>Stock</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach($lowStockProducts as $product)
                  <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                  </tr>
                  @endforeach
                </tbody>

              </table>
            </div>
          </div>
        </div>

      </div>

        <div class="col-md-12 col-xl-4">
          <h5 class="mb-3">Income Overview</h5>
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">This Week Statistics</h6>
              <h3 class="mb-3">$7,650</h3>
              <div id="income-overview-chart"></div>
            </div>
          </div>
        </div>      

        <div class="col-md-12 col-xl-4">
          <h5 class="mb-3">Analytics Report</h5>
          <div class="card">
            <div class="list-group list-group-flush">
              <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Company
                Finance Growth<span class="h5 mb-0">+45.14%</span></a>
              <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Company
                Expenses Ratio<span class="h5 mb-0">0.58%</span></a>
              <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Business
                Risk Cases<span class="h5 mb-0">Low</span></a>
            </div>
            <div class="card-body px-2">
              <div id="analytics-report-chart"></div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-xl-8">
          <h5 class="mb-3">Sales Report</h5>
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">This Week Statistics</h6>
              <h3 class="mb-0">$7,650</h3>
              <div id="sales-report-chart"></div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-xl-4">
          <h5 class="mb-3">Transaction History</h5>
          <div class="card">
            <div class="list-group list-group-flush">
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avtar avtar-s rounded-circle text-success bg-light-success">
                      <i class="ti ti-gift f-18"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Order #002434</h6>
                    <p class="mb-0 text-muted">Today, 2:00 AM</P>
                  </div>
                  <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1">+ $1,430</h6>
                    <p class="mb-0 text-muted">78%</P>
                  </div>
                </div>
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avtar avtar-s rounded-circle text-primary bg-light-primary">
                      <i class="ti ti-message-circle f-18"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Order #984947</h6>
                    <p class="mb-0 text-muted">5 August, 1:45 PM</P>
                  </div>
                  <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1">- $302</h6>
                    <p class="mb-0 text-muted">8%</P>
                  </div>
                </div>
              </a>
              <a href="#" class="list-group-item list-group-item-action">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avtar avtar-s rounded-circle text-danger bg-light-danger">
                      <i class="ti ti-settings f-18"></i>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">Order #988784</h6>
                    <p class="mb-0 text-muted">7 hours ago</P>
                  </div>
                  <div class="flex-shrink-0 text-end">
                    <h6 class="mb-1">- $682</h6>
                    <p class="mb-0 text-muted">16%</P>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    @endsection

    @section('scripts')
      <!-- <script src="{{ asset('assets/js/pages/dashboard-default.js') }}"></script> -->
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <script>
        // Grafik Penjualan 7 Hari
        const weekly = @json($weeklySales);
        const labels = weekly.map(d => d.date);
        const totals = weekly.map(d => d.total);
        const salesChart = document.getElementById('salesChart');

        if(salesChart){
          new Chart(salesChart, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                label: 'Penjualan',
                data: totals,
                borderWidth: 2,
                tension: 0,4
              }]
            }
          });
        }

      </script>
    @endsection