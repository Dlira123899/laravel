<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmailNotification;
use App\Mail\ProductsNotif;
use App\Models\Product;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::all();

            Log::info($products);
            return response()->json([
                'status' => true,
                'products' => $products,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'min:3', 'unique:' . Product::class],
                'description' => ['string'],
                'price' => ['required', 'integer']
            ]);

            /**
             * Check if current user is Authorized to create product
             * TODO: can be implemented in middleware
             */
            $this->authorize('create', Product::class);

            $product = Product::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price')
            ]);

            event(new Registered($product));

            /**
             * Push Email notification to Queue
             */
            Queue::push(new ProcessEmailNotification($product));
            // ProcessEmailNotification::dispatch($product);

            Log::info('Successfully created a product');
            Log::info($request->input());
            return response()->json([
                'status' => true,
                'message' => 'Successfully created a product',
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) throw new Exception('Product not found');

            $output = [
                'status' => true,
                'product' => $product,
            ];
            Log::info($output);
            return response()->json($output);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => ['required', 'min:3', 'unique:' . Product::class],
                'description' => ['string'],
                'price' => ['required', 'integer']
            ]);

            /**
             * Check if current user is Authorized to create product
             * TODO: can be implemented in middleware
             */
            $this->authorize('update', Product::class);

            $product = Product::find($id);
            if (!$product) throw new Exception('Product was not found');

            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->save();

            $output = [
                'status' => true,
                'message' => 'Successfully Updated',
            ];
            Log::info($output);
            return response()->json($output);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            /**
             * Check if current user is Authorized to create product
             * TODO: can be implemented in middleware
             */
            $this->authorize('delete', Product::class);

            $product = Product::find($id);
            if (!$product) throw new Exception('Product was not found');

            $deleted = $product->delete();

            $output = [
                'status' => true,
                'message' => 'Successfully Deleted',
                'data' => $deleted,
            ];
            Log::info($output);
            return response()->json($output);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
