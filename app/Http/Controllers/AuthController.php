<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private TokenService $tokenService,
    ) {}

    public function loginView()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ]);

        if (! Auth::attempt($validated)) {
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }

        return redirect()->route('bucket.index');
    }

    public function registerView()
    {
        return view('Auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        User::create($data);

        return redirect()->route('login')->with('success', 'Usuario registrado exitosamente');
    }

    public function logout()
    {
        Auth::logout();

        return view('Auth.login');
    }

    public function createToken(CreateTokenRequest $request)
    {
        try {
            $token = $this->tokenService->createToken($request->validated());

            return redirect()->route('settings.index')
                ->with('success', 'Token creado correctamente')
                ->with('plainTextToken', $token);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo crear el token: '.$e->getMessage()]);
        }
    }

    public function revoke(Request $request, string $tokenId)
    {
        $tokenName = $this->tokenService->revokeToken($tokenId, $request->user()->id);

        if (!$tokenName) {
            return redirect()->route('settings.index')
                ->with('error', 'Token no encontrado');
        }

        return redirect()->route('settings.index')
            ->with('success', "Token '{$tokenName}' eliminado correctamente");
    }
}
