@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-amber-900">All Categories</h2>
    <a href="{{ route('admin.categories.create') }}"
       class="bg-amber-900 text-white px-4 py-2 rounded-lg text-sm
              hover:bg-amber-700 transition">
        + Add Category
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden p-4">
    <table id="categoriesTable" class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Slug</th>
                <th class="px-4 py-3">Description</th>
                <th class="px-4 py-3">Products</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr class="border-b hover:bg-gray-50" id="category-{{ $category->id }}">
                <td class="px-4 py-3">{{ $category->id }}</td>
                <td class="px-4 py-3 font-semibold text-amber-900">
                    {{ $category->name }}
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">
                    {{ $category->slug }}
                </td>
                <td class="px-4 py-3 text-gray-500">
                    {{ $category->description ?? '—' }}
                </td>
                <td class="px-4 py-3">
                    <span class="bg-amber-100 text-amber-700 px-2 py-1
                                 rounded-full text-xs font-semibold">
                        {{ $category->product_count }} products
                    </span>
                </td>
                <td class="px-4 py-3">
                    @if($category->is_active)
                        <span class="bg-green-100 text-green-700 px-2 py-1
                                     rounded-full text-xs font-semibold">
                            Active
                        </span>
                    @else
                        <span class="bg-red-100 text-red-600 px-2 py-1
                                     rounded-full text-xs font-semibold">
                            Inactive
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded-lg
                                  text-xs hover:bg-blue-600 transition">
                            Edit
                        </a>
                        <button type="button"
                                class="delete-category bg-red-500 text-white px-3 py-1
                                       rounded-lg text-xs hover:bg-red-600 transition"
                                data-id="{{ $category->id }}"
                                data-url="{{ route('admin.categories.destroy', $category->id) }}">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#categoriesTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "Search categories:",
            lengthMenu: "Show _MENU_ categories",
        }
    });

    const token = $('meta[name="csrf-token"]').attr('content');

    // DELETE CATEGORY
    $(document).on('click', '.delete-category', function() {
        if (!confirm('Are you sure you want to delete this category?')) return;

        const btn = $(this);
        const url = btn.data('url');
        const id  = btn.data('id');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _method: 'DELETE', _token: token },
            success: function(response) {
                if (response.success) {
                    $('#categoriesTable').DataTable().row('#category-' + id).remove().draw();
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                showToast('error', res?.message ?? 'Something went wrong!');
            }
        });
    });

    function showToast(type, message) {
        const colors = {
            success: 'bg-green-100 border-green-400 text-green-700',
            error:   'bg-red-100 border-red-400 text-red-700',
        };
        const icon  = type === 'success' ? '✅' : '❌';
        const toast = $(`
            <div class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg border
                        shadow-lg ${colors[type]}">
                ${icon} ${message}
            </div>
        `);
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300, function() { $(this).remove(); }), 3000);
    }
});
</script>
@endpush

@endsection