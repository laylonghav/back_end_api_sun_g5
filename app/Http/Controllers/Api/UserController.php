<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    // ======= CRUD Operation =========

    // Retrieve data (Read)
    public function index()
    {
        $users = [
            ["name" => "John", "age" => 29, "gender" => "Male", "major" => "IT", "dob" => "12-12-1998"],
            ["name" => "Jen", "age" => 20, "gender" => "Male", "major" => "Web app", "dob" => "12-12-2000"],
            ["name" => "Len", "age" => 21, "gender" => "Famale", "major" => "Desktop app", "dob" => "12-12-2003"],
        ];
        return [
            "data" => $users,
            'message' => "Get users successfully."
        ];

    }

    // Store new data = Create
    public function store(Request $req)
    {
        // $name = $req->input("name");
        $input = [
            "name" => $req->input("name"),
            "username" => $req->input("username"),
            "password" => $req->input("password"),
            "gender" => $req->input("gender"),
            "age" => $req->input("age"),
            "dob" => $req->input("dob"),
            "major" => $req->input("major"),
        ];

        return [
            "data" => $input,
            'message' => "Created users successfully."
        ];
    }

    // update existing data = update
    public function update(Request $req, $id)
    {
        $input = [
            "id" => $id,
            "name" => $req->input("name"),
            "username" => $req->input("username"),
            "password" => $req->input("password"),
            "gender" => $req->input("gender"),
            "age" => $req->input("age"),
            "dob" => $req->input("dob"),
            "major" => $req->input("major"),
        ];

        return [
            "data" => $input,
            'message' => "Updated users successfully."
        ];
    }

    // Romove data = Delete
    public function destroy($id)
    {
        return [
            "id" => $id,
            'message' => "Delete users successfully."
        ];
    }
}
