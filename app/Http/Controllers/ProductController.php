<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
        ]);

        $image = $request->file('image');
        $image->storeAs('/products', $image->hashName());

        Product::create([
            'image'         => $image->hashName(),
            'title'         => $validatedData['title'],
            'description'   => $validatedData['description'],
            'price'         => $validatedData['price'],
            'stock'         => $validatedData['stock'],
        ]);

        return redirect()->route('products.index')->with('success', 'Data berhasil disimpan');
    }

    public function show(string $id): View
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('/products', $image->hashName());
            // hapus gambar lama
            Storage::delete('/products' . $product->image);
            // ganti gambar baru
            $product->image = $image->hashName();
        }

        $product->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock,
        ]);

        return redirect()->route('products.index')->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id): RedirectResponse
    {
        // cari product berdasarkan ID
        $product = Product::findOrFail($id);
        // hapus gambar
        Storage::delete('/products' . $product->image);
        // hapus data product
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Data berhasil dihapus!');
    }
}
