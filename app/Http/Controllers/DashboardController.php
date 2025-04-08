<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Customer;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function dashboard()
    {
        $totalProduk = Produk::count();
        $totalCustomer = Customer::count();
        $todayIncome = Transaksi::whereDate('tanggal_transaksi', now()->toDateString())
            ->sum('total_transaksi');
        
        // Income chart data
        $incomePerDay = Transaksi::selectRaw('DATE(tanggal_transaksi) as date, SUM(total_transaksi) as total')
            ->whereBetween('tanggal_transaksi', [now()->startOfMonth(), now()->endOfMonth()])
            ->groupBy('date')
            ->orderBy('date')
             ->pluck('total', 'date');
        
        $dates = [];
        $incomes = [];
        foreach ($incomePerDay as $date => $total) {
            $dates[] = Carbon::parse($date)->format('j M');
            $incomes[] = $total / 1000000;
        }
        
        // Top Products for current month by default
        $topProducts = $this->getTopProducts('month');
        $topCustomers = $this->getTopCustomers('month');
        
        return view('dashboard', compact(
            'totalProduk',
            'totalCustomer',
            'todayIncome',
            'dates',
            'incomes',
            'topProducts',
            'topCustomers'
        ));
    }

    public function getTopProducts($period = 'month')
    {
        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth()
        };
        
        $endDate = match($period) {
            'week' => now()->endOfWeek(),
            'month' => now()->endOfMonth(),
            'year' => now()->endOfYear(),
            default => now()->endOfMonth()
        };

        return DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id')
            ->select(
                'produk.id',
                'produk.nama_produk',
                DB::raw('SUM(detail_transaksi.jumlah_barang) as jumlah')
            )
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('produk.id', 'produk.nama_produk')
            ->orderBy(DB::raw('SUM(detail_transaksi.jumlah_barang)'), 'desc')
            ->limit(10)
            ->get();
    }

    public function getTopCustomers($period = 'month')
    {
        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth()
        };
        $endDate = match($period) {
            'week' => now()->endOfWeek(),
            'month' => now()->endOfMonth(),
            'year' => now()->endOfYear(),
            default => now()->endOfMonth()
        };

        return DB::table('transaksi')
            ->join('customer', 'transaksi.id_customer', '=', 'customer.id')
            ->select(
                'customer.id',
                'customer.nama_customer',
                DB::raw('COUNT(transaksi.id) as jumlah_transaksi'),
                DB::raw('SUM(transaksi.total_transaksi) as total_pembelian')
            )
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('customer.id', 'customer.nama_customer')
            ->orderBy('total_pembelian', 'desc')
            ->limit(5)
            ->get();
    }

    public function getIncomeData(Request $request)
    {
        $period = $request->input('period', 'month');
        
        // Define date ranges based on the selected period
        if ($period == 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
            $dateFormat = 'j M';
        } elseif ($period == 'month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
            $dateFormat = 'j M';
        } else { // year
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
            $dateFormat = 'M Y';
        }
        
        if ($period == 'year') {
            $incomeData = Transaksi::selectRaw('DATE_FORMAT(tanggal_transaksi, "%Y-%m") as month, SUM(total_transaksi) as total')
                ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month')
                ->get();
                
            $dates = [];
            $incomes = [];
            
            foreach ($incomeData as $item) {
                $dates[] = Carbon::parse($item->month . '-01')->format($dateFormat);
                $incomes[] = $item->total / 1000000;
            }
        } else {
            $incomeData = Transaksi::selectRaw('DATE(tanggal_transaksi) as date, SUM(total_transaksi) as total')
                ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                
            $dates = [];
            $incomes = [];
            
            foreach ($incomeData as $item) {
                $dates[] = Carbon::parse($item->date)->format($dateFormat);
                $incomes[] = $item->total / 1000000;
            }
        }
        
        return response()->json([
            'dates' => $dates,
            'incomes' => $incomes
        ]);
    }

    public function getFilteredData(Request $request)
    {
        $period = $request->input('period', 'month');
        $type = $request->input('type', 'products');

        if ($type == 'products') {
            $data = $this->getTopProducts($period);
        } else {
            $data = $this->getTopCustomers($period);
        }

        return response()->json($data);
    }
}