<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->find($id);

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($user->image && !str_contains($user->image, 'ui-avatars')) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $extension = $data['image']->getClientOriginalExtension();
            $fileName = 'profile_' . Str::random(20) . '.' . $extension;
            $path = $data['image']->storeAs('uploads/profiles', $fileName, 'public');

            $data['image'] = $path;
        }
        // Handle image removal
        elseif (isset($data['remove_image']) && $data['remove_image']) {
            if ($user->image && !str_contains($user->image, 'ui-avatars')) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = null;
            unset($data['remove_image']);
        }

        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->find($id);
        return $user->delete();
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByUsername($username)
    {
        return $this->model->where('username', $username)->first();
    }

    public function findByPhone($phone)
    {
        return $this->model->where('phone', $phone)->first();
    }
}
