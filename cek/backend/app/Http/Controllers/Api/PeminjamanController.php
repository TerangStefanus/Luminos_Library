<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator; // import library untuk validasi
use App\Models\Peminjaman; // import model Peminjaman
use App\Models\User;
use App\Models\Book;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::all(); 

        if (count($peminjamans) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $peminjamans
            ], 200);
        } // return data semua peminjaman dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data peminjaman kosong
    }

    // method untuk menampilkan 1 data peminjaman (search)
    public function show($id)
    {
        $peminjaman = Peminjaman::find($id); // mencari data peminjaman berdasarkan id

        if (!is_null($peminjaman)) {
            return response([
                'message' => "Retrieve peminjaman Success",
                'data' => $peminjaman
            ], 200);
        } // return data peminjaman yang ditemukan dalam bentuk json

        return response([
            'message' => 'Peminjaman Not Found',
            'data' => null
        ], 404); // return message saat data peminjaman tidak ditemukan
    }

    // method untuk menambah 1 data peminjaman baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'idUser' => 'required|numeric',
            'idBuku' => 'required|numeric'
        ]); // membuat rule validasi input

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input 

            $user = User::find($storeData['idUser']);
            $storeData['email'] = $user['email'];
            $book = Book::find($storeData['idBuku']);
            $storeData['judul'] = $book['judul'];

        if(is_null($user)||is_null($book))
            return response(['message' => 'User/Book not found'],404);

        $peminjaman = Peminjaman::create($storeData);
        return response([
            'message' => 'Add peminjaman Success',
            'data' => $peminjaman
        ], 200); // return data peminjaman baru dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id); // mencaari data product berdasarkan id

        if (is_null($peminjaman)) {
            return response ([
                'message' => 'peminjaman Not Found',
                'data' => null
            ], 404);
        } // return message saat data peminjaman tidak ditemukan

        if ($peminjaman->delete()) {
            return response([
                'message' => 'Delete peminjaman Success',
                'data' => $peminjaman
            ], 200);
        } // return message saat berhasil menghapus data peminjaman

        return response([
            'message' => 'Delete peminjaman Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data peminjaman
    }

    //method untuk mengubah 1 data peminjaman (update)
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id); // menbcaru data peminjaman berdasarkan id
        if (is_null($peminjaman)) {
            return response([
                'message' => 'peminjaman Not Found',
                'data' => null
            ], 404);
        } // return message saat data peminjaman tidak ditemukan

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

        if ($peminjaman->save()) {
            return response([
                'message' => 'Update peminjaman Success',
                'data' => $peminjaman
            ], 200);
        } // return data peminjaman yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update peminjaman Failed',
            'data' => null,
        ], 400); // return message saat peminjaman gagal di edit
    }
}
