<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator; // import library untuk validasi
use App\Models\Pengembalian; // import model Use
use App\Models\User;
use App\Models\Book;

class PengembalianController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $pengembalians = Pengembalian::all(); // mengambil semua data pengembalian

        if (count($pengembalians) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pengembalians
            ], 200);
        } // return data semua pengembalian dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data pengembalian kosong
    }

    // method untuk menampilkan 1 data pengembalian (search)
    public function show($id)
    {
        $pengembalian = Pengembalian::find($id); // mencari data pengembalian berdasarkan id

        if (!is_null($pengembalian)) {
            return response([
                'message' => "Retrieve pengembalian Success",
                'data' => $pengembalian
            ], 200);
        } // return data pengembalian yang ditemukan dalam bentuk json

        return response([
            'message' => 'pengembalian Not Found',
            'data' => null
        ], 404); // return message saat data pengembalian tidak ditemukan
    }

    // method untuk menambah 1 data pengembalian baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'idUser' => 'required|numeric',
            'idBuku' => 'required|numeric',
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input 

        $user = User::find($storeData['idUser']);
        $storeData['email'] = $user['email'];
        $book = Book::find($storeData['idBuku']);
        $storeData['judul'] = $book['judul'];
    
        if(is_null($user)||is_null($book))
            return response(['message' => 'User/Book not found'],404);

        // if(is_null($user)||is_null($book))
        //     return response(['message' => 'User/Book not found'],404);

        $pengembalian = Pengembalian::create($storeData);
        return response([
            'message' => 'Add pengembalian Success',
            'data' => $pengembalian
        ], 200); // return data pengembalian baru dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $pengembalian = Pengembalian::find($id); // mencaari data product berdasarkan id

        if (is_null($pengembalian)) {
            return response ([
                'message' => 'pengembalian Not Found',
                'data' => null
            ], 404);
        } // return message saat data pengembalian tidak ditemukan

        if ($pengembalian->delete()) {
            return response([
                'message' => 'Delete pengembalian Success',
                'data' => $pengembalian
            ], 200);
        } // return message saat berhasil menghapus data pengembalian

        return response([
            'message' => 'Delete pengembalian Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data pengembalian
    }

    //method untuk mengubah 1 data pengembalian (update)
    public function update(Request $request, $id)
    {
        $pengembalian = Pengembalian::find($id); // menbcaru data pengembalian berdasarkan id
        if (is_null($pengembalian)) {
            return response([
                'message' => 'pengembalian Not Found',
                'data' => null
            ], 404);
        } // return message saat data pengembalian tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'idUser' => 'required|numeric',
            'idBuku' => 'required|numeric',
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $user = User::find($updateData['idUser']);
        $book = Book::find($updateData['idBuku']);
    
        if(is_null($user)||is_null($book))
            return response(['message' => 'User/Book not found'],404);
        
        $pengembalian->idUser = $updateData['idUser'];
        $pengembalian->email = $user['email'];
        $pengembalian->idBuku = $updateData['idBuku'];
        $pengembalian->judul = $book['judul'];

        if ($pengembalian->save()) {
            return response([
                'message' => 'Update pengembalian Success',
                'data' => $pengembalian
            ], 200);
        } // return data pengembalian yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update pengembalian Failed',
            'data' => null,
        ], 400); // return message saat pengembalian gagal di edit
    }
}
