<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductoCollection;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Visibility;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return new ProductoCollection(Producto::orderBy('id', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required',
            'detalles'=>'required',
            'precio' => 'required',
            'imagen'=>'required',
            'categoria_id' => 'required',
            'estado' => 'required'
        ]);

        try{
            // $imageName = Str::random().'.'.$request->imagen->getClientOriginalExtension();
            // Storage::disk('public')->put('product/image', $request->imagen,$imageName);
            $imageName = Str::random().'.'.$request->imagen->getClientOriginalExtension();
            Storage::disk('public')->put('product/image/'.$imageName, file_get_contents($request->imagen), ['visibility' => Visibility::PUBLIC]);

            $producto = new Producto;
            $producto->nombre = $request->nombre;
            $producto->detalles = $request->detalles;
            $producto->precio = $request->precio;
            $producto->imagen = $imageName;
            $producto->estado = $request->estado;
            $producto->categoria_id = $request->categoria_id;
            $producto->save();

            return response()->json([
                'message'=>'Product Created Successfully!!'
            ]);
        }catch(\Exception $e){

            if (Storage::disk('public')->exists('product/image/'.$imageName)) {
                Storage::disk('public')->delete('product/image/'.$imageName);
            }
            return response()->json([
                'message'=>'Something goes wrong while creating a product!!' + $e
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $producto->estado = $request->estado;
        $producto->save();
        return [
            'producto' => $producto
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
