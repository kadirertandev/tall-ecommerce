<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\AdminCreateForm;
use App\Livewire\Forms\Admin\AdminEditForm;
use App\Models\Role;
use App\Models\User;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

class Admins extends Component
{
  use WithFileUploads;
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithSweetAlert;

  public AdminCreateForm $createForm;
  public AdminEditForm $editForm;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public $rolesFilter = [];
  public $perPage = 10;
  public $columns = [
    "full_name" => "Admin",
    "email" => "Email",
    "phone_number" => "Phone Number",
    "role" => "Role"
  ];

  #[Url()]
  public $withTrashed = false;
  public function updatedWithTrashed()
  {
    if ($this->withTrashed == true)
      $this->onlyTrashed = false;
  }

  #[Url()]
  public $onlyTrashed = false;
  public function updatedOnlyTrashed()
  {
    if ($this->onlyTrashed == true)
      $this->withTrashed = false;
  }

  #[Computed()]
  public function admins()
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
      })
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
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

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
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

  public $selectedAdmin;
  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedAdmin = User::withTrashed()->findOrFail($id);
      $this->dispatch("open-admin-view-modal");
    });
  }

  public function showEditModal($id)
  {
    $this->tryCatch(function () use ($id) {
      if (!Gate::allows("edit admins") || !Gate::allows("assign role")) {
        throw new UnauthorizedException("can not edit admin");
      }

      $admin = User::findOrFail($id);

      $this->selectedAdmin = $admin;
      $this->editForm->first_name = $admin->first_name;
      $this->editForm->last_name = $admin->last_name;
      $this->editForm->email = $admin->email;
      $this->editForm->phone_number = $admin->phone_number;
      $this->editForm->date_of_birth = $admin->date_of_birth;
      $this->editForm->profile_image = $admin->profile_image;
      $this->editForm->userId = $admin->id;
      $this->editForm->roleId = $admin->role()->id;

      $this->dispatch("open-admin-edit-modal");
    });
  }

  public function create()
  {
    $this->createForm->validate();

    $this->tryCatch(
      function () {
        if (!Gate::allows("create admins") || !Gate::allows("assign role")) {
          throw new UnauthorizedException("can not create admin");
        }

        if ($this->createForm->profile_image) {
          $imageName = $this->createForm->profile_image->store("profile_images", "public");
        }

        DB::beginTransaction();

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

        $role = Role::findOrFail($this->createForm->roleId);
        $newAdmin->assignRole(role: $role);

        DB::commit();

        $this->dispatch("close-admin-create-modal");
        $this->resetCreateFormFields();

        $this->swalToast([
          "titleText" => "Admin created successfully!"
        ]);
      },
      [
        ModelNotFoundException::class => function ($e) {
          DB::rollBack();
          $this->swalError([
            "titleText" => Str::singular(Str::ucfirst(app($e->getModel())->getTable())) . " not found!"
          ]);
        },
        Throwable::class => function ($e) {
          DB::rollBack();
          $this->swalTemplateSomethingWentWrong();
        }
      ]
    );
  }

  public function update()
  {
    $this->editForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("edit admins") || !Gate::allows("assign role")) {
        throw new UnauthorizedException("can not edit admin");
      }

      $admin = User::findOrFail($this->selectedAdmin->id);

      if ($this->editForm->profile_image) {
        $imageName = $this->editForm->profile_image->store("profile_images", "public");
        if (Storage::disk("public")->exists($this->selectedAdmin->profile_image)) {
          Storage::disk("public")->delete($this->selectedAdmin->profile_image);
        }
      }

      $admin->update([
        "first_name" => $this->editForm->first_name,
        "last_name" => $this->editForm->last_name,
        "email" => $this->editForm->email,
        "phone_number" => $this->editForm->phone_number,
        "date_of_birth" => $this->editForm->date_of_birth,
        "profile_image" => $imageName ?? $admin->profile_image
      ]);

      $role = Role::findOrFail($this->editForm->roleId);
      $admin->assignRole($role);

      $this->dispatch("close-admin-edit-modal");

      $this->swalToast([
        "titleText" => "Admin updated successfully!"
      ]);
    });
  }

  public function assignRole($adminId, $roleId)
  {
    $this->tryCatch(
      function () use ($adminId, $roleId) {
        $admin = User::findOrFail($adminId);

        if ($admin->role()->name == "owner") {
          throw new UnauthorizedException("can not change role of owner");
        }

        $role = Role::findOrFail($roleId);
        $admin->assignRole($role);

        $this->swalToast([
          "titleText" => "Admin updated successfully!"
        ]);
      }
    );
  }

  public function askDeleteAdmin($adminId, $permanently = false)
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to delete this admin " . ($permanently ? "permanently?" : "?"),
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "No",
      "onConfirm" => (!$permanently ? "" : "force-") . "delete-admin-confirmed",
      "onConfirmParameters" => [
        "adminId" => $adminId
      ],
      "customClass" => [
        "title" => "text-nowrap!",
        "popup" => "min-w-max!"
      ]
    ]);
  }

  #[On("delete-admin-confirmed")]
  public function delete($adminId)
  {
    $this->tryCatch(function () use ($adminId) {
      if (!Gate::allows("delete admins") || auth()->user()->id == $adminId) {
        throw new UnauthorizedException("can not delete admin");
      }

      $admin = User::findOrFail($adminId);

      $admin->delete();
      $admin->update([
        "deleted_by" => auth()->user()->id
      ]);

      $this->swalToast([
        "titleText" => "Admin deleted successfully!"
      ]);
    });
  }

  #[On("force-delete-admin-confirmed")]
  public function forceDelete($adminId)
  {
    $this->tryCatch(function () use ($adminId) {
      if (!Gate::allows("force delete admins")) {
        throw new UnauthorizedException("you cant delete admins permanently");
      }

      $admin = User::withTrashed()->findOrFail($adminId);

      $admin->forceDelete();

      $this->swalToast([
        "titleText" => "Admin deleted permanently successfully!"
      ]);
    });
  }

  public function restore($adminId)
  {
    $this->tryCatch(function () use ($adminId) {
      if (!Gate::allows("force delete admins")) {
        throw new UnauthorizedException("you cant restore admin");
      }

      User::withTrashed()->findOrFail($adminId)->restore();

      $this->swalToast([
        "titleText" => "Admin restored successfully!"
      ]);
    });
  }

  public function render()
  {
    return view('livewire.admin.admins')
      ->layout("components.admin-layout", ["title" => "Admins"])
      ->section("content");
  }
}
