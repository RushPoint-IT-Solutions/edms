<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TypeOfDocumentController extends Controller
{
    public function index()
    {
        return view('settings.documents_type.index');
    }
}
