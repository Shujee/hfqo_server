<?php
namespace App\Http\Controllers;

class SPAController extends Controller
{
    function handleAny() 
    {
        return view('welcome');
    }
}