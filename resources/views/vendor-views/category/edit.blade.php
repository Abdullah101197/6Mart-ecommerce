@extends('layouts.vendor.app')

@section('title', translate('Edit Category'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('assets/admin/img/categories.png')}}" class="w--20" alt="">
                </span>
                <span>{{ translate('Edit Category') }}</span>
            </h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('vendor.category.update', $category->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="input-label">{{ translate('Category Name') }}</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $category->name) }}" required>
                    </div>

                    <div class="form-group d-flex align-items-center" style="gap:10px">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" id="cat_status" name="status" value="1" {{ old('status', (int) $category->status) ? 'checked' : '' }}>
                        <label for="cat_status" class="mb-0">{{ translate('Active') }}</label>
                    </div>

                    <div class="d-flex justify-content-end" style="gap:10px">
                        <a class="btn btn--reset" href="{{ route('vendor.category.add') }}">{{ translate('Cancel') }}</a>
                        <button class="btn btn--primary" type="submit">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

