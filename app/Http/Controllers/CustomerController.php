<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Ramsey\Uuid\Guid\Guid;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Customer::query();
        
        if ($search) {
            $query->where('nama_customer', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
        }
        
        $customers = $query->orderBy('created_at', 'desc')
                           ->paginate(20);
        
        return view('customer.customer', compact('customers', 'search'));
    }

    // Menampilkan form tambah customer
    public function create()
    {
        return view('customer.tambahcustomer');
    }

    // Menyimpan customer baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
        ]);

        Customer::create([
            'id' => Guid::uuid4()->toString(),
            'nama_customer' => $request->nama_customer,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('customer')->with('success', 'Customer berhasil ditambahkan!');
    }

    // Menampilkan form edit customer
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.editcustomer', compact('customer'));
    }

    // Memperbarui data customer
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update([
            'nama_customer' => $request->nama_customer,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('customer')->with('success', 'Customer berhasil diperbarui!');
    }

    // Menghapus customer
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customer')->with('success', 'Customer berhasil dihapus!');
    }
}