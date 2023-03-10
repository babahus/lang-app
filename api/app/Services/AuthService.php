<?php
namespace App\Services;

use App\Models\{
    User,
    Dictionary,
};
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
    public function register(RegisterDTO $registerDTO): User
    {
        $encryptPassword = Hash::make($registerDTO->password);
        $user = User::create([
            'name'     => $registerDTO->name,
            'email'    => $registerDTO->email,
            'password' => $encryptPassword
        ]);
        // when user is created, we attach to him default role (User)
        // and create empty dictionary for exercises
        $user->roles()->attach(1);
        $createdDictionary = $this->dictionaryService->createEmptyDictionary();
        $user->exercises()->attach($createdDictionary->id,['type' => Dictionary::class]);

        return $user;
    }

    /**
     * @param LoginDTO $loginDTO
     * @return \Illuminate\Contracts\Auth\Authenticatable|bool
     */
    public function login(LoginDTO $loginDTO): \Illuminate\Contracts\Auth\Authenticatable|bool
    {
        if (!auth()->attempt(([
            'email'    => $loginDTO->email,
            'password' => $loginDTO->password
        ]))) {

            return false;
        }

        return Auth::user();
    }

    /**
     * @param User|\Laravel\Socialite\Two\User $user
     * @param string $provider
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User
     */
    public function findOrCreateUser(User|\Laravel\Socialite\Two\User $user, string $provider): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|User
    {
        // if the user already exists, return it
        $authUser = User::where('email', $user->email)->first();

        if ($authUser) {

            return $authUser;
        }
        // otherwise create a new user and return it
        $hashPassword = Hash::make(Str::random(16));
        $authUser = User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'password' => $hashPassword
        ]);
        $authUser->roles()->attach(1);
        $createdDictionary = $this->dictionaryService->createEmptyDictionary();
        $user->exercises()->attach($createdDictionary->id,['type' => Dictionary::class]);

        return $authUser;
    }
}
