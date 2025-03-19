<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use Illuminate\Http\Request;

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
     *             @OA\Property(property="make", type="string", example="Toyota"),
     *             @OA\Property(property="model", type="string", example="Camry"),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Phone $phone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phone $phone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phone $phone)
    {
        //
    }
}
