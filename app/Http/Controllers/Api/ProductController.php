<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Afficher tous les produits
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    // Ajouter un produit
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99|decimal:0,2',
        ]);

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    // Afficher un produit
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    // Modifier un produit
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0|max:999999.99|decimal:0,2',
        ]);

        $product->update($data);

        return response()->json($product, 200);
    }

    // Supprimer un produit
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès'
        ], 200);
    }
}
