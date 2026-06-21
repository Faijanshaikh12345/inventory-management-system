<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
            'email.email' => 'Invalid email format.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalide credentials.',
        ])->onlyInput('email');
    }



    public function showRegistrationForm()
    {
        return view('register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'contact' => ['nullable', 'string', 'max:20', 'unique:users,contact'],
            'image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'agree'    => ['required'],
        ], [
            'name.required'  => 'Name is required.',
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
            'contact.required' => 'Contact number is required.',
            'agree.required' => 'You must agree to the terms.',
            'contact.unique' => 'This contact number is already registered.',
        ]);

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $imageName);
        }

        // 1. Create the user FIRST
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'contact'  => $request->contact,
            'image'    => $imageName,
        ]);
        Auth::login($user);

        return redirect()->route('dashboard');
    }


    // dashboard function 
    public function dashboard()
    {
        $userId = Auth::id();

        $categoriesCount = Category::where('user_id', $userId)->count();
        $unitsCount      = Unit::where('user_id', $userId)->count();
        $suppliersCount  = Supplier::where('user_id', $userId)->count();
        $customersCount  = Customer::where('user_id', $userId)->count();
        $productsCount   = Product::where('user_id', $userId)->count();

        $purchaseCount   = Purchase::where('user_id', $userId)->count();
        $salesCount      = Sale::where('user_id', $userId)->count();

        $totalPurchaseAmount = Purchase::where('user_id', $userId)
            ->sum('total_amount');

        $totalSalesAmount = Sale::where('user_id', $userId)
            ->sum('grand_total');

        $lowStockCount = Product::where('user_id', $userId)
            ->where('stock_qty', '<=', 10)
            ->count();

        return view('dashboard', compact(
            'categoriesCount',
            'unitsCount',
            'suppliersCount',
            'customersCount',
            'productsCount',
            'purchaseCount',
            'salesCount',
            'totalPurchaseAmount',
            'totalSalesAmount',
            'lowStockCount'
        ));
    }


    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}