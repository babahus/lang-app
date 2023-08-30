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
        // when user is created, we attach to him default role (User)
        // and create empty dictionary for exercises

        $user->roles()->attach($roleObj->id);
        $createdDictionary = $this->dictionaryService->createEmptyDictionary();
        $user->exercises()->attach($createdDictionary->id,['exercise_type' => Dictionary::class]);

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
    public function findOrCreateUser(User|\Laravel\Socialite\Two\User $user, string $provider): array
    {
        // if the user already exists, return it
        $authUser = User::where('email', $user->email)->first();

        if ($authUser) {
            $token = $this->createToken($authUser);

            return ['user' => $authUser, 'token' => $token];
        }
        // otherwise create a new user and return it
        $hashPassword = Hash::make(Str::random(16));
        $authUser = User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'password' => $hashPassword
        ]);
        // it is necessary to pass the user role in the request
        $authUser->roles()->attach(1);
        $createdDictionary = $this->dictionaryService->createEmptyDictionary();

        $authUser->exercises()->attach($createdDictionary->id,['exercise_type' => Dictionary::class]);
        $roleName = $authUser->roles->where('id', 1)->first()->name;

        event(new UserAuthorized($authUser, $roleName, $token));

        return ['user' => $authUser, 'token' => $token];
    }

    private function createToken(User $user)
    {
        return $user->createToken('authToken')->plainTextToken;
    }
}
