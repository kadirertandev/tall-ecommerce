<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\AdminCreateForm;
use App\Livewire\Forms\Admin\AdminEditForm;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

class Admins extends Component
{
  use WithFileUploads;
  use WithPagination;
  public $page;
  public function updatedPage()
  {
    $this->dispatch("refresh-flowbite");
  }

  public AdminCreateForm $createForm;
  public AdminEditForm $editForm;

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public function updatedKeyword()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $rolesFilter = [];
  public function updatedRolesFilter()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $perPage = 10;
  public function updatedPerPage()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $withTrashed = false;
  public $onlyTrashed = false;
  public function updatedWithTrashed()
  {
    $this->withTrashed == true ? $this->onlyTrashed = false : "";
    $this->dispatch("refresh-flowbite");

  }
  public function updatedOnlyTrashed()
  {
    $this->onlyTrashed == true ? $this->withTrashed = false : "";
    $this->dispatch("refresh-flowbite");
  }

  #[Computed()]
  public function adminsTemplate()
  {
    return User::where("is_admin", true)->search($this->keyword)
      ->when($this->sortBy != "full_name" && $this->sortBy != "role", function ($query) {
        $query->when($this->sortBy && $this->sortDir, function ($query) {
          return $query->orderBy($this->sortBy, $this->sortDir);
        });
      })
      ->when($this->sortBy == "full_name", function ($query) {
        $query->when($this->sortBy && $this->sortDir, function ($query) {
          return $query->orderBy(DB::raw("CONCAT(first_name, ' ', last_name)"), $this->sortDir);
        });
      })
      ->when($this->sortBy == "role", function ($query) {
        $query->join("model_has_roles", "users.id", "=", "model_has_roles.user_id")
          ->join("roles", "roles.id", "=", "model_has_roles.role_id")
          ->select("users.*", DB::raw("roles.name as role_name"))
          ->orderBy("role_name", $this->sortDir);
      })
      ->when($this->rolesFilter, function ($query) {
        $query->when($this->sortBy != "role", function ($query) {
          $query->join("model_has_roles", "users.id", "=", "model_has_roles.user_id")
            ->join("roles", "roles.id", "=", "model_has_roles.role_id")
            ->select("users.*", DB::raw("roles.id as role_id"));
        })->whereIn("role_id", $this->rolesFilter);
      })
      ->when($this->withTrashed == true, function ($query) {
        $query->withTrashed();
      })
      ->when($this->onlyTrashed == true, function ($query) {
        $query->onlyTrashed();
      });
  }

  #[Computed()]
  public function admins()
  {
    return $this->adminsTemplate->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function roles()
  {
    $roles = Role::all();

    if (auth()->user()->role()->name == "owner") {
      $ownerRole = Role::where("name", "owner")->first();
      $roles = $roles->except([$ownerRole->id]);
    }
    if (auth()->user()->role()->name == "super_admin") {
      $ownerRole = Role::where("name", "owner")->first();
      $superAdminRole = Role::where("name", "super_admin")->first();
      $roles = $roles->except([$ownerRole->id, $superAdminRole->id]);
    }
    return $roles;
  }

  #[Computed()]
  public function columns()
  {
    return [
      "full_name" => "Admin",
      "email" => "Email",
      "phone_number" => "Phone Number",
      "role" => "Role"
    ];
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
    $this->dispatch("refresh-flowbite");
  }

  public $selectedAdmin;
  public function showViewModal($id)
  {
    $this->selectedAdmin = User::withTrashed()->find($id);
    $this->dispatch("open-admin-view-modal");
  }

  public function showEditModal($id)
  {
    $user = User::withTrashed()->find($id);
    $this->selectedAdmin = $user;
    $this->editForm->first_name = $user->first_name;
    $this->editForm->last_name = $user->last_name;
    $this->editForm->email = $user->email;
    $this->editForm->phone_number = $user->phone_number;
    $this->editForm->date_of_birth = $user->date_of_birth;
    $this->editForm->profile_image = $user->profile_image;
    $this->editForm->userId = $user->id;
    $this->editForm->roleId = $user->role()->id;
    $this->dispatch("open-admin-edit-modal");
  }

  public function assignRole($adminId, $roleId)
  {
    try {
      $admin = User::find($adminId);
      if ($admin->role()->name == "owner") {
        throw new Exception("can not change role of owner");
      }
      $role = Role::find($roleId);
      $admin->assignRole($role);
      $this->dispatch("update_admin_success");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function update()
  {
    try {
      if (!Gate::allows("edit admins") || !Gate::allows("assign role")) {
        throw new UnauthorizedException("can not edit admin");
      }

      $this->editForm->validate();

      if ($this->editForm->profile_image) {
        $imageName = $this->editForm->profile_image->store("profile_images", "public");
        if (Storage::disk("public")->exists($this->selectedAdmin->profile_image)) {
          Storage::disk("public")->delete($this->selectedAdmin->profile_image);
        }
      }

      $this->selectedAdmin->update([
        "first_name" => $this->editForm->first_name,
        "last_name" => $this->editForm->last_name,
        "email" => $this->editForm->email,
        "phone_number" => $this->editForm->phone_number,
        "date_of_birth" => $this->editForm->date_of_birth,
        "profile_image" => $imageName ?? $this->selectedAdmin->profile_image
      ]);

      $role = Role::find($this->editForm->roleId);
      $this->selectedAdmin->assignRole($role);

      $this->dispatch("close-admin-edit-modal");
      $this->dispatch("update_admin_success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    }
  }

  public function create()
  {
    // $this->authorize("create products");
    try {
      if (!Gate::allows("create admins") || !Gate::allows("assign role")) {
        throw new UnauthorizedException("can not create admin");
      }

      $this->createForm->validate();

      if ($this->createForm->profile_image) {
        $imageName = $this->createForm->profile_image->store("profile_images", "public");
      }

      $newAdmin = User::create([
        "is_admin" => true,
        "first_name" => $this->createForm->first_name,
        "last_name" => $this->createForm->last_name,
        "email" => $this->createForm->email,
        "phone_number" => $this->createForm->phone_number,
        "date_of_birth" => $this->createForm->date_of_birth,
        "profile_image" => $imageName ?? NULL,
        "password" => Hash::make($this->createForm->password),
      ]);

      $role = Role::find($this->createForm->roleId);
      $newAdmin->assignRole($role);

      $this->dispatch("close-admin-create-modal");
      $this->resetCreateFormFields();
      $this->dispatch("create_admin_success");
      $this->dispatch("refresh-flowbite");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    }
  }

  public function resetCreateFormFields()
  {
    $this->createForm->reset();
    $this->createForm->resetErrorBag();
  }

  public function removeImage()
  {
    $this->editForm->reset("profile_image");
    $this->editForm->resetErrorBag("profile_image");
    $this->createForm->reset("profile_image");
    $this->createForm->resetErrorBag("profile_image");
  }

  #[On("delete-admin-modal-is-confirmed")]
  public function delete($adminId)
  {
    // $this->authorize("delete products");
    try {
      if (!Gate::allows("delete admins") || auth()->user()->id == $adminId) {
        throw new UnauthorizedException("can not delete admin");
      }

      $admin = User::find($adminId);

      $admin->delete();
      $admin->update([
        "deleted_by" => auth()->user()->id
      ]);
      $this->dispatch("delete_admin_success");

    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    }
  }

  #[On("force-delete-admin-modal-is-confirmed")]
  public function forceDelete($adminId)
  {
    // $this->authorize("force delete products");
    try {
      if (!Gate::allows("force delete admins")) {
        throw new UnauthorizedException("you cant delete admins permanently");
      }
      $admin = User::withTrashed()->find($adminId);
      $admin->forceDelete();
      $this->dispatch("force-delete_admin_success");
    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    }
  }

  public function restore($adminId)
  {
    try {
      if (!Gate::allows("force delete admins")) {
        throw new UnauthorizedException("you cant restore admin");
      }
      User::withTrashed()->find($adminId)->restore();
    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    }
  }

  #[On("delete_admin_success")]
  public function render()
  {
    return view('livewire.admin.admins')->layout("components.admin-layout", ["title" => "Admins"])->section("content");
  }
}
