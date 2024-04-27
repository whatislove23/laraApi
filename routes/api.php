<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::group(["prefix" => "auth"], function () {
    Route::post("/register", [AuthController::class, "register"])->name("register");
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/logout", [AuthController::class, "logout"])->name("logout")->middleware('auth:sanctum');
});
Route::group(["prefix" => "categories", "middleware" => ["auth:sanctum"]], function () {
    Route::get("/", [CategoryController::class, "categories"])->name("categories");
    Route::get("/{id}", [CategoryController::class, "category"])->name("category");
    Route::get("/{id}/products", [CategoryController::class, "categoryProducts"])->name("products");
    Route::get("/{id}/products/{productId}", [CategoryController::class, "categoryProduct"])->name("product");

    Route::put("/{id}/products/{productId}/update", [CategoryController::class, "productUpdate"])->name("product.update");
    Route::put("/{id}/update", [CategoryController::class, "categoryUpdate"])->name("category.update");

    Route::post("/add-product", [CategoryController::class, "addProduct"])->name("create-product");
    Route::post("/add-category", [CategoryController::class, "addCategory"])->name("create-category");
});