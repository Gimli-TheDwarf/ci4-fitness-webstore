<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use Google_Client;
use Google_Service_Oauth2;

class SignInController extends BaseController
{
    public function Main()
    {
        return view('SignIn');
    }

    public function LogIn()
    {
        return view('LogIn');
    }

    public function LogOut()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Logged Out Successfully.');
    }

    public function SignUp()
    { 
        $data = $this->request->getPost();
        $userModel = new \App\Models\UserModel();
        $productsUsersModel = new \App\Models\UsersProductsModel();

        $email = trim((string) ($data['email'] ?? ''));
        $username = trim((string) ($data['username'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $confirmPassword = (string) ($data['confirm_password'] ?? '');

        if (
            ! filter_var($email, FILTER_VALIDATE_EMAIL)
            || ! preg_match('/^[a-zA-Z0-9_]{3,24}$/', $username)
            || strlen($password) < 8
            || $password !== $confirmPassword
            || $userModel->where('username', $username)->first()
            || $userModel->where('email', $email)->first()
        )
        {
            return redirect()->back()->with('error', 'Sign Up Unsuccessful.');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // PASSWORD_DEFAULT tracks current PHP password hashing guidance.

        $newUserId = $userModel->insert([
            'email' => $email,
            'username' => $username,
            'password' => $hashedPassword,
            'role' => 'user'
        ]);

        $newUser = $userModel->find($newUserId);

        $cartCount = $productsUsersModel->getCartCount($newUserId);

        session()->regenerate(true);
        session()->set([
            'user_id' => $newUserId,
            'username' => $newUser['username'],
            'role' => $newUser['role'],
            'cart_items_count' => $cartCount,
            'isLoggedIn' => true,
        ]);
    
        return redirect()->to('homepage')->with('success', 'Successfully Signed Up.');
    }

    public function LogInInfo()
    {
        $data = $this->request->getPost();
        $userModel = new \App\Models\UserModel();
        $productsUsersModel = new \App\Models\UsersProductsModel();

        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $user = $userModel->where('email', $email)->first();
        
        if ($user && (int) ($user['is_active'] ?? 1) === 1 && password_verify($password, $user['password']))
        {
            $cartCount = $productsUsersModel->getCartCount($user['id']);

            session()->regenerate(true);
            session()->set([ //setting new session (cookie) variables that will expire within two hours cuz of ci4 default
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'cart_items_count' => $cartCount,
                'isLoggedIn' => true,
            ]);
            return redirect()->to('homepage')->with('success', 'Successfully Logged In.'); 
        }

        return redirect()->to('login')->with('error', 'Invalid Credentials.'); //redirects user to login page. the stuff inside with is a temporary flashdata message that you can display in the view. 
        //we dont have to return this stuff with setstatuscode cuz that shit is only used for ajax requests or apis
    }

    public function google()//login/signin through google basically 
    {
        $client = new Google_Client();//making a new instance of google's oauth client for handling logins n stuff
        $client->setClientId(env('GOOGLE_CLIENT_ID')); //basically tells google which app is requesting login 
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET')); //something similar to a password to 'prove' to google that my server is on my app
        $client->setRedirectUri(base_url('auth/google/callback')); //tells google where to go after a user logs in
        $client->addScope('email'); //requests google to access user's email 
        $client->addScope('profile'); //requests google to access user's profile info - name
        $state = bin2hex(random_bytes(16));
        session()->set('google_oauth_state', $state);
        $client->setState($state);

        return redirect()->to($client->createAuthUrl()); //redirects the users to the login page on google
    }

    public function googleCallback()//handling response from google login/signin 
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(base_url('auth/google/callback')); //we are defining the same route here - think of this as a verification for security reasons that it is 100% the correct route 

        $state = (string) $this->request->getGet('state');
        $sessionState = (string) session()->get('google_oauth_state');
        session()->remove('google_oauth_state');

        if (! $state || ! $sessionState || ! hash_equals($sessionState, $state)) 
        {
            return redirect()->to('login')->with('error', 'Google login failed.');
        }
    
        if ($this->request->getGet('code')) //checks if google sent back a code parameter in the url.
        {
            $token = $client->fetchAccessTokenWithAuthCode($this->request->getGet('code')); //basically upon getting the code (contains credentials and other info) we then ask google for the perms to access the token (user info)
            if (! isset($token['access_token'])) 
            {
                return redirect()->to('login')->with('error', 'Google login failed.');
            }
            $client->setAccessToken($token['access_token']);//extracting the access_token from the token we requested and assigning it to the client
            $oauth = new \Google_Service_Oauth2($client); //basically we are defining a service that will fetch the token info
            $userInfo = $oauth->userinfo->get(); //what actually fetches the userinfo 
            $userModel = new \App\Models\UserModel(); //the rest is self explanatory, make a model, search for a user, if present then...
            $user = $userModel->where('email', $userInfo->email)->first();
    
            if (!$user)
            {
                $userModel->insert([
                    'email' => $userInfo->email, 
                    'username' => $userInfo->name,
                    'password' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT),
                    'role' => 'user',
                ]);
                $user = $userModel->where('email', $userInfo->email)->first();

            }

            if ((int) ($user['is_active'] ?? 1) !== 1) 
            {
                return redirect()->to('login')->with('error', 'Invalid Credentials.');
            }

            $productsUsersModel = new \App\Models\UsersProductsModel();
            $cartCount = $productsUsersModel->getCartCount($user['id']);

            session()->regenerate(true);
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'cart_items_count' => $cartCount,
                'email' => $user['email'],
                'isLoggedIn' => true,
            ]);
    
            return redirect()->to('homepage')->with('success', 'Successfully Logged In.');
        }
    
        return redirect()->to('login')->with('error', 'Google login failed.'); 
    }
}
