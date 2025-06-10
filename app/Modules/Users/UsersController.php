<?php

namespace Fylari\Modules\Users;

use Fylari\Core\Controller;
use Fylari\Core\DB;
use Fylari\Middleware\Auth as JWT;

class UsersController extends Controller
{
    private JWT $jwt;

    public function __construct()
    {
        $this->jwt = new JWT();

        // Применяем защиту к выбранным маршрутам
        $no_protected = [
            '/users/login',
            '/users/register'
        ];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!in_array($uri, $no_protected)) {
            $this->jwt->verify();
        }
    }

    public function register(): void
    {
        $data = $this->input();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $name = $data['name'] ?? '';

        if (!$email || !$password) {
            $this->json(['error' => 'Email and password required'], 400);
            return;
        }

        if (DB::table('users')->where('email', '=', $email)->first()) {
            $this->json(['error' => 'User already exists'], 409);
            return;
        }

        $userId = DB::table('users')->insert([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'name' => $name,
            'is_admin' => 0
        ]);

        $token = $this->jwt->generateToken($userId);

        $this->json([
            'token' => $token,
            'user' => ['id' => $userId, 'email' => $email, 'name' => $name]
        ]);
    }

    public function create(): void
    {
        $userId = $_SERVER['user_id'] ?? null;
        if (!$userId) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $admin = DB::table('users')->where('id', '=', $userId)->first();
        if (!$admin || !$admin['is_admin']) {
            $this->json(['error' => 'Forbidden. Admins only.'], 403);
            return;
        }

        $data = $this->input();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $name = $data['name'] ?? '';
        $isAdmin = isset($data['is_admin']) ? (int)$data['is_admin'] : 0;

        if (!$email || !$password) {
            $this->json(['error' => 'Email and password required'], 400);
            return;
        }

        if (DB::table('users')->where('email', '=', $email)->first()) {
            $this->json(['error' => 'User already exists'], 409);
            return;
        }

        $newUserId = DB::table('users')->insert([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'name' => $name,
            'is_admin' => $isAdmin
        ]);

        $this->json(['message' => 'User created', 'id' => $newUserId]);
    }


    public function login(): void
    {
        $data = $this->input();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = DB::table('users')->where('email', '=', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            $this->json(['error' => 'Invalid credentials'], 401);
            return;
        }

        $token = $this->jwt->generateToken((int)$user['id']);

        $this->json(['token' => $token, 'user' => ['id' => $user['id'], 'email' => $user['email'], 'name' => $user['name']]]);
    }

    public function me(): void
    {
        $userId = $_SERVER['user_id'] ?? null;

        if (!$userId) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $user = DB::table('users')->where('id', '=', $userId)->first();
        unset($user['password']);
        $this->json($user);
    }

    public function logout(): void
    {
        $this->json(['message' => 'Client should delete token.']);
    }

    public function updateProfile(): void
    {
        $userId = $_SERVER['user_id'] ?? null;
        if (!$userId) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $data = $this->input();
        $fields = [];

        if (!empty($data['name']))  $fields['name']  = $data['name'];
        if (!empty($data['email'])) $fields['email'] = $data['email'];

        if (empty($fields)) {
            $this->json(['error' => 'No fields to update'], 400);
            return;
        }

        // Email уникальность
        if (isset($fields['email'])) {
            $exists = DB::table('users')
                ->where('email', '=', $fields['email'])
                ->where('id', '!=', $userId)
                ->first();
            if ($exists) {
                $this->json(['error' => 'Email already taken'], 409);
                return;
            }
        }

        DB::table('users')->where('id', '=', $userId)->update($fields);

        $user = DB::table('users')->where('id', '=', $userId)->first();
        unset($user['password']);

        $this->json(['message' => 'Profile updated', 'user' => $user]);
    }

    public function changePassword(): void
    {
        $userId = $_SERVER['user_id'] ?? null;
        if (!$userId) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $data = $this->input();
        $current = $data['current_password'] ?? '';
        $new     = $data['new_password'] ?? '';

        if (!$current || !$new) {
            $this->json(['error' => 'Current and new password required'], 400);
            return;
        }

        $user = DB::table('users')->where('id', '=', $userId)->first();

        if (!$user || !password_verify($current, $user['password'])) {
            $this->json(['error' => 'Current password incorrect'], 403);
            return;
        }

        $hash = password_hash($new, PASSWORD_BCRYPT);
        DB::table('users')->where('id', '=', $userId)->update(['password' => $hash]);

        $this->json(['message' => 'Password changed successfully']);
    }

    public function index(): void
    {
        $userId = $_SERVER['user_id'] ?? null;
        if (!$userId) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $admin = DB::table('users')->where('id', '=', $userId)->first();
        if (!$admin || !$admin['is_admin']) {
            $this->json(['error' => 'Forbidden. Admins only.'], 403);
            return;
        }

        $users = DB::table('users')->get();

        // Убираем пароли
        foreach ($users as &$u) {
            unset($u['password']);
        }

        $this->json($users);
    }

    public function show($id): void
    {

            $this->me();

    }
    public function update($id): void
    {
        $data = $this->input();
        $fields = [];

        if (isset($data['name'])) $fields['name'] = $data['name'];
        if (isset($data['email'])) $fields['email'] = $data['email'];
        if (isset($data['is_admin'])) $fields['is_admin'] = (int)$data['is_admin'];
        if (isset($data['status'])) $fields['status'] = (int)$data['status'];
        if (!empty($data['password'])) $fields['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        if (empty($fields)) {
            $this->json(['error' => 'No fields to update'], 400);
            return;
        }

        $exists = DB::table('users')->where('id', '=', $id)->first();
        if (!$exists) {
            $this->json(['error' => 'User not found'], 404);
            return;
        }

        DB::table('users')->where('id', '=', $id)->update($fields);

        $this->json(['message' => 'User updated']);
    }



}
