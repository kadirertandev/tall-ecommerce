<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\AdminCreateForm;
use App\Livewire\Forms\Admin\AdminEditForm;
use App\Models\Role;
use App\Models\User;
use App\Traits\WithSoftDeleteFilter;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithRemoveFormImage;
use App\Traits\WithSweetAlert;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithTryCatch;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
  use WithInteractModal;
  use WithSoftDeleteFilter;
  use WithRemoveFormImage;
  use WithTableSortAndFilter;

  public AdminCreateForm $createForm;
  public AdminEditForm $editForm;

  public function mount()
  {
    $this->validateOrderByInputs();
  }

  public function boot()
  {
    $this->refreshFlobwite();
  }

  #[Url()]
  public $rolesFilter = [];

  public $columns = [
    "full_name" => "Admin",
    "email" => "Email",
    "phone_number" => "Phone Number",
    "role_name" => "Role"
  ];

  #[Computed()]
  public function allowedColumns()
  {
    return array_keys($this->columns);
  }

  #[Computed()]
  public function authRole()
  {
    return auth()->user()->getRoleName();
  }

  #[Computed()]
  public function admins()
  {
    return User::where("is_admin", true)
      ->search($this->keyword)
      ->withOnlyNecesssaryColumns()
      ->withRoleIdAndRoleName()
      ->join("model_has_roles", "users.id", "model_has_roles.model_id")
      ->filterByTrashed($this->withTrashed, $this->onlyTrashed)
      ->filterByRole($this->rolesFilter)
      ->sortByColumn($this->orderByColumn, $this->orderByDirection)
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function roles()
  {
    return Role::when($this->authRole !== "owner", function ($query) {
      return $query->whereNot("name", "=", "super_admin");
    })
      ->whereNot("name", "=", "owner")
      ->get(["id", "name"]);
  }

  public $selectedAdmin;

  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedAdmin = User::withTrashed()->findOrFail($id);
      $this->showModal("view-admin");
    });
  }

  public function showEditModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->authorize("edit admins");

      $admin = User::findOrFail($id);

      $this->selectedAdmin = $admin;
      $this->editForm->first_name = $admin->first_name;
      $this->editForm->last_name = $admin->last_name;
      $this->editForm->email = $admin->email;
      $this->editForm->phone_number = $admin->phone_number;
      $this->editForm->date_of_birth = $admin->date_of_birth;
      $this->editForm->userId = $admin->id;
      $this->editForm->roleId = $admin->getRoleId();

      $this->showModal("edit-admin");
    });
  }

  public function afterModalClosed($modalName)
  {
    if ($modalName == "view-admin") {
      $this->reset("selectedAdmin");
    }
  }

  public function create()
  {
    $this->tryCatch(
      function () {
        $this->createForm->validate();

        $this->authorize("create admins");

        if ($this->createForm->image) {
          $imageName = $this->createForm->image->store("profile_images", "public");
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
        $newAdmin->syncRoles(role: $role->name);

        DB::commit();

        $this->closeModal("create-admin");

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
    $this->tryCatch(function () {
      $this->editForm->validate();

      $this->authorize("edit admins");

      $admin = User::findOrFail($this->selectedAdmin->id);

      if ($this->editForm->image) {
        $imageName = $this->editForm->image->store("profile_images", "public");
        if (Storage::disk("public")->exists($this->selectedAdmin->profile_image ?? "")) {
          Storage::disk("public")->delete($this->selectedAdmin->profile_image ?? "");
        }
      }

      DB::beginTransaction();

      $admin->update([
        "first_name" => $this->editForm->first_name,
        "last_name" => $this->editForm->last_name,
        "email" => $this->editForm->email,
        "phone_number" => $this->editForm->phone_number,
        "date_of_birth" => $this->editForm->date_of_birth,
        "profile_image" => $imageName ?? $admin->profile_image,
        "updated_at" => now()
      ]);

      $role = Role::findOrFail($this->editForm->roleId);
      $admin->syncRoles($role->name);

      DB::commit();

      $this->selectedAdmin = $admin;

      $this->closeModal("edit-admin");

      $this->swalToast([
        "titleText" => "Admin updated successfully!"
      ]);
    }, [
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
    ]);
  }

  public function assignRole($adminId, $roleId)
  {
    $this->tryCatch(
      function () use ($adminId, $roleId) {
        $admin = User::findOrFail($adminId);
        $role = Role::findOrFail($roleId);

        $this->authorize("assignRole", [$admin, $role]);

        $admin->syncRoles($role->name);

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
      $admin = User::findOrFail($adminId);

      $this->authorize("deleteAdmin", $admin);

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
      $this->authorize("force delete admins");

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
      $this->authorize("force delete admins");

      $admin = User::withTrashed()->findOrFail($adminId);
      $admin->restore();
      $admin->update([
        "deleted_by" => null
      ]);

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
