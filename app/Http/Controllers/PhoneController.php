<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhoneController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/Phones",
     *     summary="Get paginated list of Phones",
     *     tags={"Phones"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number to retrieve",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated Phones list",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="company", type="string", example="apple"),
     *                     @OA\Property(property="model", type="string", example="iphone 16 Pro"),
     *                     @OA\Property(property="quantity", type="integer", example=190),
     *                     @OA\Property(property="price", type="string", example=1099)
     *                 )
     *             ),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="total_pages", type="integer", example=5),
     *             @OA\Property(property="total_items", type="integer", example=15),
     *             @OA\Property(property="per_page", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $perPage = 3;
        $phone = Phone::paginate($perPage);

        return response()->json([
            'data' => $phone->items(),
            'current_page' => $phone->currentPage(),
            'total_pages' => $phone->lastPage(),
            'total_items' => $phone->total(),
            'per_page' => $phone->perPage(),
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/phones",
     *     summary="Create a new phone",
     *     tags={"phones"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="phone data",
     *         @OA\JsonContent(
     *             required={"company", "model", "quantity", "price"},
     *             @OA\Property(property="make", type="string", example="apple"),
     *             @OA\Property(property="model", type="string", example="iphone 16 pro"),
     *             @OA\Property(property="year", type="integer", example=2020),
     *             @OA\Property(property="price", type="string", example=1099)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="phone created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="phone Created Successfully"),
     *             @OA\Property(property="phone", ref="#/components/schemas/phone")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validatedphone = Validator::make(
                $request->all(),
                [
                    'company' => 'required|string|max:255',
                    'model' => 'required|string|max:255',
                    'quantity' => 'required|integer|digits:5',
                    'price' => 'required|numeric|min:0.01',
                ]
            );
            if ($validatedphone->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatedphone->errors()
                ], 401);
            }

            $phone = Phone::create([
                'company' => $request->company,
                'model' => $request->model,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'phone Created Successfully',
                'phone' => $phone
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/phones/{id}",
     *     summary="Retrieve phone details",
     *     tags={"phones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the phone to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="phone retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="phone retrieved successfully"),
     *             @OA\Property(property="phone", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="make", type="string", example="apple"),
     *                 @OA\Property(property="model", type="string", example="iphone 16 pro"),
     *                 @OA\Property(property="year", type="integer", example=2020),
     *                 @OA\Property(property="price", type="string", example=1099)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="phone not found")
     * )
     */
    public function show(Phone $phone)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'phone Existes',
                'phone' => $phone
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/phones/{id}",
     *     summary="Update an existing phone",
     *     tags={"phones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the phone to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated phone data",
     *         @OA\JsonContent(
     *             required={"company", "model", "quantity", "price"},
     *             @OA\Property(property="make", type="string", example="apple"),
     *             @OA\Property(property="model", type="string", example="iphone 16 pro"),
     *             @OA\Property(property="year", type="integer", example=2020),
     *             @OA\Property(property="price", type="string", example=1099)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="phone updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="phone updated successfully"),
     *             @OA\Property(property="phone", ref="#/components/schemas/phone")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="phone not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function update(Request $request, Phone $phone)
    {
        $phone->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'phone updated successfully',
            'phone' => $phone
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/phones/{id}",
     *     summary="Delete a phone",
     *     tags={"phones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the phone to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="phone deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="phone not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function destroy(phone $phone)
    {
        $phone->delete();
        return response()->json([
            'status' => true,
            'message' => 'phone deleted successfully'
        ], 204);
    }
}
