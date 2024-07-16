<x-header-meta />

<div class="min-h-screen">
    <x-header :nav1=true :nav2=true :nav3=true />
    <div class="main-container mt-4">
        <p>total_permissions_count: {{ $total_permissions_count }}</p>
        <p>my total_permissions_count: {{ count(auth()->user()->permissions) }}</p>
        <p>Role Name: {{ auth()->user()->role()->name }}</p>
        <p>Foreach roles -> role name</p>
        @foreach (auth()->user()->roles as $role)
            <p>{{ $role->name }}</p>
        @endforeach
        <p>End-Foreach roles -> role name</p>
        <hr class="bg-black h-2">
        <p>Permissions: <span>{{ auth()->user()->permissions()->pluck('name') }}</span></p>
        @foreach (auth()->user()->permissions as $permission)
            <p>{{ $permission->name }}</p>
        @endforeach
        <hr class="bg-black h-2">
        <div class="grid grid-cols-5">
            <div class="flex flex-col">
                <a href="{{ route('view-products') }}">View Products</a>
                <a href="{{ route('create-products') }}">Create Products</a>
                <a href="{{ route('edit-products') }}">Edit Products</a>
                <a href="{{ route('delete-products') }}">Delete Products</a>
            </div>
            <div class="flex flex-col">
                <a href="{{ route('view-categories') }}">View Categories</a>
                <a href="{{ route('create-categories') }}">Create Categories</a>
                <a href="{{ route('edit-categories') }}">Edit Categories</a>
                <a href="{{ route('delete-categories') }}">Delete Categories</a>
            </div>
            <div class="flex flex-col">
                <a href="{{ route('view-brands') }}">View Brands</a>
                <a href="{{ route('create-brands') }}">Create Brands</a>
                <a href="{{ route('edit-brands') }}">Edit Brands</a>
                <a href="{{ route('delete-brands') }}">Delete Brands</a>
            </div>
            <div class="flex flex-col">
                <a href="{{ route('view-reviews') }}">View Reviews</a>
                <a href="{{ route('create-reviews') }}">Create Reviews</a>
                <a href="{{ route('edit-reviews') }}">Edit Reviews</a>
                <a href="{{ route('delete-reviews') }}">Delete Reviews</a>
            </div>
            <div class="flex flex-col">
                <a href="{{ route('view-admins') }}">View Admins</a>
                <a href="{{ route('create-admins') }}">Create Admins</a>
                <a href="{{ route('edit-admins') }}">Edit Admins</a>
                <a href="{{ route('delete-admins') }}">Delete Admins</a>
            </div>
        </div>
        <hr class="bg-black h-2">
        @can('view admins')
            <p>can directive view admins</p>
        @endcan
        @can('create admins')
            <p>can directive create admins</p>
        @endcan
        @can('edit admins')
            <p>can directive edit admins</p>
        @endcan
        @can('delete admins')
            <p>can directive delete admins</p>
        @endcan

        @can('view products')
            <p>can directive view products</p>
        @endcan
        @can('create products')
            <p>can directive create products</p>
        @endcan
        @can('edit products')
            <p>can directive edit products</p>
        @endcan
        @can('delete products')
            <p>can directive delete products</p>
        @endcan

        @can('view categories')
            <p>can directive view categories</p>
        @endcan
        @can('create categories')
            <p>can directive create categories</p>
        @endcan
        @can('edit categories')
            <p>can directive edit categories</p>
        @endcan
        @can('delete categories')
            <p>can directive delete categories</p>
        @endcan

        @can('view brands')
            <p>can directive view brands</p>
        @endcan
        @can('create brands')
            <p>can directive create brands</p>
        @endcan
        @can('edit brands')
            <p>can directive edit brands</p>
        @endcan
        @can('delete brands')
            <p>can directive delete brands</p>
        @endcan

        @can('view reviews')
            <p>can directive view reviews</p>
        @endcan
        @can('create reviews')
            <p>can directive create reviews</p>
        @endcan
        @can('edit reviews')
            <p>can directive edit reviews</p>
        @endcan
        @can('delete reviews')
            <p>can directive delete reviews</p>
        @endcan
    </div>
</div>

<x-footer />
<x-footer-meta>
    <x-slot:script>
        <script>
            // alert("roles-permissions-test")
        </script>
    </x-slot>
</x-footer-meta>
