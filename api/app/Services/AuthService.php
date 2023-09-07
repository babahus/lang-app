<?php
namespace App\Services;

use App\Events\UserAuthorized;
use App\Models\{Role, User, Dictionary};
use App\DataTransfers\{
    LoginDTO,
    RegisterDTO,
};
use Illuminate\Support\{
    Str,
    Facades\Auth,
    Facades\Hash,
};
use App\Contracts\AuthContract;
use Illuminate\Auth\Events\Registered;

final class AuthService implements AuthContract
{
    /**
     * @var DictionaryService
     */
    private DictionaryService $dictionaryService;

    /**
     * @param DictionaryService $dictionaryService
     */
    public function __construct(DictionaryService $dictionaryService){
        $this->dictionaryService = $dictionaryService;
    }

    /**
     * @param RegisterDTO $registerDTO
     * @return User
     */
    public function register(RegisterDTO $registerDTO): array
    {
        $roleObj = Role::whereName($registerDTO->role)->first();

        $encryptPassword = Hash::make($registerDTO->password);
        $user = User::create([
            'name'     => $registerDTO->name,
            'email'    => $registerDTO->email,
            'password' => $encryptPassword
        ]);

        $user->roles()->attach($roleObj->id);

        //creating an empty dictionary and attaching it to a user

        $token = $this->createToken($user);
        event(new UserAuthorized($user, $registerDTO->role, $token));

        return ['user' => $user, 'token' => $token];
    }

    /**
     * @param LoginDTO $loginDTO
     * @return \Illuminate\Contracts\Auth\Authenticatable|bool
     */
    public function login(LoginDTO $loginDTO): bool|array
    {
        if (!auth()->attempt(([
            'email'    => $loginDTO->email,
            'password' => $loginDTO->password
        ]))) {

            return false;
        }

        $user = Auth::user();
        $token = $this->createToken($user);

        event(new UserAuthorized(Auth::user(), $loginDTO->role, $token));

        return ['user' => $user, 'token' => $token];
    }

    /**
     * @param User|\Laravel\Socialite\Two\User $user
     * @param string $provider
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User
     */
    public function findOrCreateUser(User|\Laravel\Socialite\Two\User $user, string $provider, string $role): array | bool
    {
        $authUser = User::where('email', $user->email)->first();
        $objRole = Role::where('name', $role)->first();

        if ($authUser) {
            if (!$authUser->hasRole($role)) {
                return false;
            }

            $authUser->email_verified_at = now();
            $authUser->save();

            $token = $this->createToken($authUser);
            event(new UserAuthorized($authUser, $objRole->name, $token));

            return ['user' => $authUser, 'token' => $token];
        }

        return $this->createNewUser($user, $objRole);
    }

    private function createNewUser($user, $objRole)
    {
        $hashPassword = Hash::make(Str::random(16));

        $newUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $hashPassword,
        ]);

        $newUser->roles()->attach($objRole->id);

        $token = $this->createToken($newUser);
        event(new UserAuthorized($newUser, $objRole->name, $token));

        return ['user' => $newUser, 'token' => $token];
    }

    public function createToken(User $user)
    {
        return $user->createToken('authToken')->plainTextToken;
    }
}
