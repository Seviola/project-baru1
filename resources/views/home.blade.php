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
        <!-- Row 1 -->
          <div class="col-md-6 col-xl-4">
              <div class="card bg-primary text-white">
                  <div class="card-body">
                      <h6 class="mb-2 text-white">Total Produk</h6>
                      <h4>{{ $totalProducts }}</h4>
                  </div>
              </div>
          </div>

          <div class="col-md-6 col-xl-4">
              <div class="card bg-info text-white">
                  <div class="card-body">
                      <h6 class="mb-2 text-white">Transaksi Hari Ini</h6>
                      <h4>{{ $totalTransactionsToday }}</h4>
                  </div>
              </div>
          </div>

          <div class="col-md-6 col-xl-4">
              <div class="card bg-danger text-white">
                  <div class="card-body">
                      <h6 class="mb-2 text-white">Transaksi Bulan Ini</h6>
                      <h4>{{ $totalTransactionsMonth }}</h4>
                  </div>
              </div>
          </div>

          <!-- Row 2 -->
          <div class="col-md-6 col-xl-6">
              <div class="card bg-success text-white">
                  <div class="card-body">
                      <h6 class="mb-2 text-white">Pendapatan Hari Ini</h6>
                      <h4>Rp {{ number_format($todayIncome,0,',','.') }}</h4>
                  </div>
              </div>
          </div>

          <div class="col-md-6 col-xl-6">
              <div class="card bg-warning text-white">
                  <div class="card-body">
                      <h6 class="mb-2 text-white">Pendapatan Bulan Ini</h6>
                      <h4>Rp {{ number_format($monthIncome,0,',','.') }}</h4>
                  </div>
              </div>
          </div>

      <div class="row">

    <div class="col-lg-7">
        <div class="card shadow border-0 rounded-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1 text-primary">
                            Kelas Yang Populer</h4>
                        <p class="text-muted mb-0">
                            Ranking kelas dengan jumlah peserta terbanyak
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary fs-6 px-3 py-2">
                            {{ count($topProducts) }} Kelas
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted">
                                <th style="width: 80px;">
                                    Ranking</th>
                                <th>
                                    Nama Kelas</th>
                                <th class="text-center">
                                    Peserta</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($topProducts as $index => $product)
                            <tr class="border-bottom">
                                <td>
                                    @if($index == 0)
                                        <span class="badge bg-success text-dark px-3 py-2 rounded-0">
                                            🥇 #1
                                        </span>
                                    @elseif($index == 1)
                                        <span class="badge bg-primary px-3 py-2 rounded-0">
                                            🥈 #2
                                        </span>
                                    @elseif($index == 2)
                                        <span class="badge bg-danger px-3 py-2 rounded-0">
                                            🥉 #3
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-0">
                                            #{{ $index + 1 }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold fs-6">
                                        {{ $product->product_name }} - {{ $product->class_type }}</div>
                                    <small class="text-muted">
                                        Kelas Banyak Diikuti
                                      </small>
                                </td>
                                <td class="text-center">
                                    <div class="fw-bold text-success fs-5">
                                        {{ $product->total_sold }}</div>
                                    <small class="text-muted">
                                        peserta</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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