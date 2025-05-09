<?php 
 
namespace App\Http\Controllers; 
 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator; 
use GuzzleHttp\Client; 
use Illuminate\Support\Facades\Cookie; 
 
class AuthController extends Controller 
{ 
    public function loginPage() 
    { 
        return view('auth.login'); 
    } 
 
    public function login(Request $request) 
    {
        $request->validate([ 
            'username' => 'required|string', 
            'password' => 'required|string', 
        ]); 
 
        try { 
            $client = new Client(); 
            $apiLoginUrl = env('APP_URL') . '/api/login'; 
 
            $response = $client->post($apiLoginUrl, [ 
                'headers' => [ 
                    'Accept' => 'application/json', 
                    'Content-Type' => 'application/json', 
                ], 
                'json' => [ 
                    'username' => $request->username, 
                    'password' => $request->password, 
                ], 
            ]); 
 
            $data = json_decode($response->getBody(), true); 
 
            if ($data['success'] === true && isset($data['data']['access_token'])) {
                session(['access_token' => $data['data']['access_token']]);
                session(['user' => $data['data']['user'] ?? null]);
                
                $cookieTime = 60;
                
                Cookie::queue('access_token', $data['data']['access_token'], $cookieTime);
                
                \Log::info('Token diterima: ' . substr($data['data']['access_token'], 0, 10) . '...');
                
                return response()->view('dashboard.index', [ 
                    'token' => $data['data']['access_token'], 
                    'user' => $data['data']['user'] ?? null, 
                ]);
            } else { 
                $errorMessage = $data['message'] ?? 'Login failed. Please check your username and password.'; 
                return redirect()->route('loginPage')->with('error', $errorMessage); 
            } 
        } catch (\GuzzleHttp\Exception\ClientException $e) { 
            $response = $e->getResponse(); 
            $errorBody = json_decode($response->getBody()->getContents(), true); 
            $errorMessage = $errorBody['message'] ?? 'Login failed: Invalid credentials.'; 
            return back()->withErrors(['login' => $errorMessage]); 
        } catch (\Exception $e) { 
            \Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors(['login' => 'An error occurred: ' . $e->getMessage()]); 
        } 
    }
    
    public function checkToken()
    {
        $sessionToken = session('access_token');
        $cookieToken = request()->cookie('access_token');
        
        return response()->json([
            'cookie_exists' => !empty($cookieToken),
            'session_exists' => !empty($sessionToken),
            'cookie_preview' => $cookieToken ? substr($cookieToken, 0, 10) . '...' : null,
        ]);
    }
    
    // Metode untuk keluar/logout
    public function logout()
    {
        session()->forget(['access_token', 'user']);
        Cookie::queue(Cookie::forget('access_token'));
        
        return redirect()->route('loginPage')->with('success', 'You have been logged out successfully');
    }
}