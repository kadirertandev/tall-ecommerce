<?php

namespace Tests\Feature\Profile;

use App\Models\UserAddress;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\UserProfile\UserProfileAddresses;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;


class UserProfileAddressesTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $admin;

  private $endPoint;

  public function setUp(): void
  {
    parent::setUp();

    $this->endPoint = route("auth.user.addresses");

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();
  }

  public function test_component_exists_on_the_page()
  {
    $this->actingAs($this->user)
      ->get($this->endPoint)
      ->assertOk()
      ->assertSeeLivewire(UserProfileAddresses::class);
  }

  public function test_it_redirects_to_login_page_when_unauthenticated_users_try_to_access_user_profile_addresses_page()
  {
    $this->get($this->endPoint)
      ->assertRedirect(route("login"));
  }

  public function test_admins_can_not_access()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $this->owner = $this->createAdmin("owner");
    $this->admin = $this->createAdmin("admin");

    $this->actingAs($this->owner)
      ->get($this->endPoint)
      ->assertStatus(403);

    $this->actingAs($this->admin)
      ->get($this->endPoint)
      ->assertStatus(403);
  }

  public function test_users_can_visit_their_addresses_page()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileAddresses::class)
      ->assertStatus(200)
      ->assertSeeHtml('<h1 class="text-3xl">Addresses</h1>')
      ->assertSeeText('Add new address');
  }

  public function test_does_not_show_any_address_when_addresses_is_empty()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileAddresses::class)
      ->assertStatus(200)
      ->assertSeeHtmlInOrder([
        '<h1 class="text-3xl">Addresses</h1>',
        '<h1 class="my-2 text-xl">No address found.</h1>'
      ]);

    $this->assertEquals(0, $this->user->reviews()->count());
  }

  public function test_does_show_addresses_when_addresses_is_not_empty()
  {
    $this->actingAs($this->user);

    $addresses = UserAddress::factory(3)
      ->state(new Sequence(
        ["is_default" => true],
        ["is_default" => false],
        ["is_default" => false],
      ))
      ->for($this->user)
      ->create();

    #creates an flatten array that includes addresses' information in sequence
    $htmlInOrder = function () use ($addresses) {
      return $addresses->map(function ($address) {
        return [
          '<h1 class="text-2xl font-semibold font-roboto">' . $address->title . '</h1>',
          $address->is_default ? '<h1 class="font-semibold text-orange-400 uppercase">Default Address</h1>' : '',
          '<p class="font-thin text-black font-roboto">' . $address->neighborhood . '</p>',
          '<p class="font-thin text-black font-roboto">' . $address->address_line . '</p>',
          '<p>' . $address->district . ' / ' . $address->city . '</p>'
        ];
      })->flatten()->toArray();
    };

    $component = Livewire::test(UserProfileAddresses::class)
      ->assertStatus(200)
      ->assertSeeHtml('<h1 class="text-3xl">Addresses</h1>')
      ->assertSeeHtmlInOrder($htmlInOrder());

    $this->assertDatabaseCount("user_addresses", 3);
    $this->assertEquals(3, $this->user->addresses()->count());
    $this->assertEquals(3, count($component->addresses));
  }

  public function test_users_can_create_address()
  {
    $this->actingAs($this->user);

    $component = Livewire::test(UserProfileAddresses::class)
      ->set("form.addressTitle", "Home")
      ->set("form.selectedCity", "Some City")
      ->set("form.selectedDistrict", "Some District")
      ->set("form.selectedNeighborhood", "Some Neighborhood")
      ->set("form.addressLine", "Some address line...")
      ->set("form.makeDefault", true)
      ->call("create")
      ->assertDispatched("close-modal", name: "new-address")
      ->assertDispatched(
        "swal-fire",
        titleText: "Address created successfully!"
      );

    $this->assertDatabaseCount("user_addresses", 1);
    $this->assertDatabaseHas("user_addresses", [
      "user_id" => $this->user->id,
      "title" => "Home",
      "city" => "Some City",
      "district" => "Some District",
      "neighborhood" => "Some Neighborhood",
      "address_line" => "Some address line...",
      "is_default" => true,
    ]);
  }

  public function test_users_can_not_create_address__with_invalid_fields()
  {
    $this->actingAs($this->user);

    $component = Livewire::test(UserProfileAddresses::class)
      ->set("form.addressTitle", "ab")
      ->set("form.selectedCity", "")
      ->set("form.selectedDistrict", "")
      ->set("form.selectedNeighborhood", "")
      ->set("form.addressLine", "Some ad.")
      ->set("form.makeDefault", false)
      ->call("create")
      ->assertHasErrors(["form.addressTitle" => "The address title field must be at least 3 characters."])
      ->assertHasErrors(["form.selectedCity" => "The selected city field is required."])
      ->assertHasErrors(["form.selectedDistrict" => "The selected district field is required."])
      ->assertHasErrors(["form.selectedNeighborhood" => "The selected neighborhood field is required."])
      ->assertHasErrors(["form.addressLine" => "The address line field must be at least 10 characters."]);

    $this->assertDatabaseCount("user_addresses", 0);
  }

  public function test_users_can_update_addresses()
  {
    $this->actingAs($this->user);

    $address = UserAddress::factory()
      ->for($this->user)
      ->create();

    $component = Livewire::test(UserProfileAddresses::class)
      ->call("edit", $address->id)
      ->assertSet("form.addressTitle", $address->title)
      ->assertSet("form.selectedCity", $address->city)
      ->assertSet("form.selectedDistrict", $address->district)
      ->assertSet("form.selectedNeighborhood", $address->neighborhood)
      ->assertSet("form.addressLine", $address->address_line)
      ->assertSet("form.makeDefault", (bool) $address->is_default)
      ->assertDispatched("open-modal", name: "edit-address");

    $component->set("form.addressTitle", "Home")
      ->set("form.selectedCity", "Some City")
      ->set("form.selectedDistrict", "Some District")
      ->set("form.selectedNeighborhood", "Some Neighborhood")
      ->set("form.addressLine", "Some address line...")
      ->set("form.makeDefault", true)
      ->call("update")
      ->assertDispatched("close-modal", name: "edit-address")
      ->assertDispatched("address-updated")
      ->assertDispatched("swal-fire", titleText: "Address updated successfully!");

    $this->assertEquals([
      "addressTitle" => "",
      "selectedCity" => "",
      "selectedDistrict" => "",
      "selectedNeighborhood" => "",
      "addressLine" => "",
      "makeDefault" => false
    ], $component->form->toArray());

    $this->assertDatabaseCount("user_addresses", 1);
    $this->assertDatabaseHas("user_addresses", [
      "id" => $address->id,
      "user_id" => $this->user->id,
      "title" => "Home",
      "city" => "Some City",
      "district" => "Some District",
      "neighborhood" => "Some Neighborhood",
      "address_line" => "Some address line...",
      "is_default" => true,
    ]);

  }

  public function test_users_can_not_update_addresses_do_not_belong_to_them()
  {
    $this->actingAs($this->user);
    $anotherUser = $this->createUser();

    $address = UserAddress::factory()
      ->for($anotherUser)
      ->create();

    Livewire::test(UserProfileAddresses::class)
      ->call("edit", $address->id)
      ->assertDispatched("swal-fire", titleText: self::$authorizationExceptionMessage);

    Livewire::test(UserProfileAddresses::class)
      ->set("selectedAddressId", $address->id)
      ->set("form.addressTitle", "Home")
      ->set("form.selectedCity", "Some City")
      ->set("form.selectedDistrict", "Some District")
      ->set("form.selectedNeighborhood", "Some Neighborhood")
      ->set("form.addressLine", "Some address line...")
      ->set("form.makeDefault", true)
      ->call("update")
      ->assertDispatched("swal-fire", titleText: self::$authorizationExceptionMessage);

    $this->assertDatabaseCount("user_addresses", 1);
    $this->assertDatabaseHas("user_addresses", [
      "id" => $address->id,
      "user_id" => $anotherUser->id,
      "title" => $address->title,
      "city" => $address->city,
      "district" => $address->district,
      "neighborhood" => $address->neighborhood,
      "address_line" => $address->address_line,
      "is_default" => false,
    ]);
  }

  public function test_users_can_not_update_address__with_invalid_fields()
  {
    $this->actingAs($this->user);

    $address = UserAddress::factory()
      ->for($this->user)
      ->create();

    $component = Livewire::test(UserProfileAddresses::class)
      ->call("edit", $address->id)
      ->assertSet("form.addressTitle", $address->title)
      ->assertSet("form.selectedCity", $address->city)
      ->assertSet("form.selectedDistrict", $address->district)
      ->assertSet("form.selectedNeighborhood", $address->neighborhood)
      ->assertSet("form.addressLine", $address->address_line)
      ->assertSet("form.makeDefault", (bool) $address->is_default)
      ->assertDispatched("open-modal", name: "edit-address");

    $component->set("form.addressTitle", "ab")
      ->set("form.selectedCity", "")
      ->set("form.selectedDistrict", "")
      ->set("form.selectedNeighborhood", "")
      ->set("form.addressLine", "Some ad.")
      ->set("form.makeDefault", true)
      ->call("update")
      ->assertHasErrors(["form.addressTitle" => "The address title field must be at least 3 characters."])
      ->assertHasErrors(["form.selectedCity" => "The selected city field is required."])
      ->assertHasErrors(["form.selectedDistrict" => "The selected district field is required."])
      ->assertHasErrors(["form.selectedNeighborhood" => "The selected neighborhood field is required."])
      ->assertHasErrors(["form.addressLine" => "The address line field must be at least 10 characters."]);
    ;

    $this->assertDatabaseCount("user_addresses", 1);
    $this->assertDatabaseHas("user_addresses", [
      "id" => $address->id,
      "user_id" => $this->user->id,
      "title" => $address->title,
      "city" => $address->city,
      "district" => $address->district,
      "neighborhood" => $address->neighborhood,
      "address_line" => $address->address_line,
      "is_default" => false,
    ]);

  }

  public function test_handles_model_not_found_exception_and_dispatches_error_when_trying_to_edit_non_existing_address()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileAddresses::class)
      ->call("edit", 9999)
      ->assertDispatched("swal-fire", titleText: "User_address not found!");
  }

  public function test_users_can_delete_addresses()
  {
    $this->actingAs($this->user);

    $address = UserAddress::factory()
      ->for($this->user)
      ->create();

    $component = Livewire::test(UserProfileAddresses::class);

    $this->assertDatabaseCount("user_addresses", 1);
    $this->assertCount(1, $component->addresses);

    $component->call("askDeleteAddress", $address->id)
      ->assertDispatched(
        "swal-fire",
        icon: "question",
        titleText: "Are you sure you want to delete this address?",
        onConfirm: "delete-address-confirmed",
        onConfirmParameters: [
          "addressId" => $address->id
        ]
      );

    $component->call("delete", $address->id)
      ->assertDispatched("swal-fire", titleText: "Address deleted successfully!")
      ->assertDispatched("address-deleted");

    $this->assertDatabaseCount("user_addresses", 0);
  }

  public function test_users_can_not_delete_addresses_do_not_belong_to_them()
  {
    $this->actingAs($this->user);

    $anotherUser = $this->createUser();

    $address = UserAddress::factory()
      ->for($anotherUser)
      ->create();

    $this->assertDatabaseCount("user_addresses", 1);

    Livewire::test(UserProfileAddresses::class)
      ->call("delete", $address->id)
      ->assertDispatched("swal-fire", titleText: self::$authorizationExceptionMessage);

    $this->assertDatabaseCount("user_addresses", 1);
    $this->assertDatabaseHas("user_addresses", [
      "id" => $address->id,
      "user_id" => $anotherUser->id,
      "title" => $address->title,
      "city" => $address->city,
      "district" => $address->district,
      "neighborhood" => $address->neighborhood,
      "address_line" => $address->address_line,
      "is_default" => false,
    ]);
  }

  public function test_handles_model_not_found_exception_and_dispatches_error_when_trying_to_delete_non_existing_address()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileAddresses::class)
      ->call("delete", 9999)
      ->assertDispatched("swal-fire", titleText: "User_address not found!");
  }
}
