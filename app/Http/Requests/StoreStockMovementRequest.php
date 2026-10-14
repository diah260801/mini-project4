<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $productId = $this->input('product_id');
            $type = $this->input('type');
            $qty = (int) $this->input('quantity');

            if ($productId && $type === 'out' && $qty > 0) {
                $product = Product::find($productId);
                if ($product && $qty > $product->stock) {
                    $validator->errors()->add(
                        'quantity',
                        "Jumlah stok keluar ({$qty}) melebihi stok yang tersedia saat ini ({$product->stock}). Business rule P4: stok tidak boleh bernilai negatif."
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk yang dipilih tidak ditemukan.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'type.required' => 'Tipe transaksi stok (Masuk / Keluar) wajib dipilih.',
            'type.in' => 'Tipe transaksi stok harus berupa Masuk (in) atau Keluar (out).',
            'quantity.required' => 'Jumlah kuantitas stok wajib diisi.',
            'quantity.integer' => 'Kuantitas stok harus berupa bilangan bulat.',
            'quantity.min' => 'Kuantitas transaksi minimal 1 unit.',
            'note.max' => 'Catatan transaksi maksimal 255 karakter.',
        ];
    }
}
