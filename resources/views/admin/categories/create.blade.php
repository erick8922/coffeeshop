@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')

<div class="max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-amber-900">Add New Category</h2>
        <a href="{{ route('admin.categories.index') }}"
           class="text-sm text-amber-600 hover:underline">← Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form id="createCategoryForm">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Category Name
                </label>
                <input type="text" name="name" id="name"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="Ex: Hot Coffee">
                <p class="text-red-500 text-xs mt-1 hidden" id="nameError"></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-amber-500"
                    placeholder="Describe this category..."></textarea>
            </div>

            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active"
                       value="1" checked>
                <label for="is_active" class="text-sm text-gray-700">
                    Active (visible to customers)
                </label>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-amber-900 text-white py-3 rounded-xl font-semibold
                       hover:bg-amber-700 transition">
                Save Category
            </button>
        </form>
    </div>
</div>

{{-- HIDDEN URLs para sa JS --}}
<div id="categoryStoreUrl" data-url="{{ route('admin.categories.store') }}" class="hidden"></div>
<div id="categoryIndexUrl" data-url="{{ route('admin.categories.index') }}" class="hidden"></div>

@push('scripts')
<script src="{{ asset('js/admin/categories.js') }}"></script>
@endpush

@endsection