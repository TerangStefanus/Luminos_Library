<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Book;

class BookController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $books = book::all(); // mengambil semua data book

        if (count($books) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $books
            ], 200);
        } // return data semua book dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data book kosong
    }

    // method untuk menampilkan 1 data book (search)
    public function show($id)
    {
        $book = book::find($id); // mencari data book berdasarkan id

        if (!is_null($book)) {
            return response([
                'message' => "Retrieve book Success",
                'data' => $book
            ], 200);
        } // return data book yang ditemukan dalam bentuk json

        return response([
            'message' => 'book Not Found',
            'data' => null
        ], 404); // return message saat data book tidak ditemukan
    }

    // method untuk menambah 1 data book baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'images' => 'required',
            'judul' => 'required',
            'quantity' => 'required|numeric',
            'halaman' => 'required|numeric',
            'penulis' => 'required',
            'category' => 'required',
            'genre' => 'required',
            'description'=>'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input 

        $book = book::create($storeData);
        return response([
            'message' => 'Add book Success',
            'data' => $book
        ], 200); // return data book baru dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $book = book::find($id); // mencaari data product berdasarkan id

        if (is_null($book)) {
            return response ([
                'message' => 'book Not Found',
                'data' => null
            ], 404);
        } // return message saat data book tidak ditemukan

        if ($book->delete()) {
            return response([
                'message' => 'Delete book Success',
                'data' => $book
            ], 200);
        } // return message saat berhasil menghapus data book

        return response([
            'message' => 'Delete book Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data book
    }

    //method untuk mengubah 1 data book (update)
    public function update(Request $request, $id)
    {
        $book = book::find($id); // menbcaru data book berdasarkan id
        if (is_null($book)) {
            return response([
                'message' => 'book Not Found',
                'data' => null
            ], 404);
        } // return message saat data book tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'images' => 'required',
            'judul' => 'required',
            'quantity' => 'required|numeric',
            'halaman' => 'required|numeric',
            'penulis' => 'required',
            'category' => 'required',
            'genre' => 'required',
            'description'=>'required'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $book->images = $updateData['images'];
        $book->judul = $updateData['judul'];
        $book->quantity = $updateData['quantity'];
        $book->halaman = $updateData['halaman'];
        $book->penulis = $updateData['penulis'];
        $book->category = $updateData['category'];
        $book->genre = $updateData['genre'];
        $book->description = $updateData['description'];

        if ($book->save()) {
            return response([
                'message' => 'Update book Success',
                'data' => $book
            ], 200);
        } // return data book yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update book Failed',
            'data' => null,
        ], 400); // return message saat book gagal di edit
    }
}
