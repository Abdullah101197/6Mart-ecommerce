@extends('layouts.vendor.app')

@section('title', translate('Edit Discount'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('assets/admin/img/category.png')}}" class="w--20" alt="">
                </span>
                <span>{{ translate('Edit Discount') }}</span>
            </h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('vendor.category.discounts.update', $discount->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Category') }}</label>
                                <select class="form-control" name="category_id" required>
                                    @foreach(($categories ?? []) as $cat)
                                        <option value="{{ $cat->id }}" {{ (string) old('category_id', $discount->category_id)===(string) $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Discount Type') }}</label>
                                <select class="form-control" name="discount_type" required>
                                    <option value="percent" {{ old('discount_type', $discount->discount_type)==='percent'?'selected':'' }}>%</option>
                                    <option value="amount" {{ old('discount_type', $discount->discount_type)==='amount'?'selected':'' }}>{{ translate('Amount') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Discount') }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="discount" value="{{ old('discount', $discount->discount) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Maximum discount') }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="max_discount" value="{{ old('max_discount', $discount->max_discount) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group d-flex align-items-center mt-3" style="gap:10px">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" id="cd_status" name="status" value="1" {{ old('status', $discount->status) ? 'checked' : '' }}>
                        <label for="cd_status" class="mb-0">{{ translate('Active') }}</label>
                    </div>

                    <div class="d-flex justify-content-end" style="gap:10px">
                        <a class="btn btn--reset" href="{{ route('vendor.category.discounts.index') }}">{{ translate('Cancel') }}</a>
                        <button class="btn btn--primary" type="submit">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

