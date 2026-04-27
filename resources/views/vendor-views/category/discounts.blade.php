@extends('layouts.vendor.app')

@section('title', translate('Category Discounts'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('assets/admin/img/category.png')}}" class="w--20" alt="">
                </span>
                <span>{{ translate('Category Discounts') }}</span>
            </h1>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header py-2">
                        <h5 class="mb-0">{{ translate('Add Discount') }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('vendor.category.discounts.store') }}">
                            @csrf

                            <div class="form-group">
                                <label class="input-label">{{ translate('Category') }}</label>
                                <select class="form-control" name="category_id" required>
                                    <option value="" selected disabled>{{ translate('Select') }}</option>
                                    @foreach(($categories ?? []) as $cat)
                                        <option value="{{ $cat->id }}" {{ (string) old('category_id')===(string) $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{ translate('Discount Type') }}</label>
                                <select class="form-control" name="discount_type" required>
                                    <option value="percent" {{ old('discount_type','percent')==='percent'?'selected':'' }}>%</option>
                                    <option value="amount" {{ old('discount_type')==='amount'?'selected':'' }}>{{ translate('Amount') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{ translate('Discount') }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="discount" value="{{ old('discount') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="input-label">{{ translate('Maximum discount') }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="max_discount" value="{{ old('max_discount') }}">
                            </div>

                            <div class="form-group d-flex align-items-center" style="gap:10px">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="cd_status" name="status" value="1" {{ old('status',1) ? 'checked' : '' }}>
                                <label for="cd_status" class="mb-0">{{ translate('Active') }}</label>
                            </div>

                            <button class="btn btn--primary w-100" type="submit">{{ translate('Save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ translate('Discount list') }}</h5>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('vendor.category.add') }}">{{ translate('Back to Categories') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('Category') }}</th>
                                    <th>{{ translate('Discount') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th>{{ translate('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse(($discounts ?? []) as $k => $d)
                                    <tr>
                                        <td>{{ $k + $discounts->firstItem() }}</td>
                                        <td class="font-weight-bold">{{ $d->category?->name ?? '—' }}</td>
                                        <td>
                                            @if(($d->discount_type ?? 'percent') === 'amount')
                                                {{ \App\CentralLogics\Helpers::format_currency((float) ($d->discount ?? 0)) }}
                                            @else
                                                {{ number_format((float) ($d->discount ?? 0), 1) }}%
                                            @endif
                                        </td>
                                        <td>
                                            @if($d->status)
                                                <span class="badge badge-soft-success">{{ translate('Active') }}</span>
                                            @else
                                                <span class="badge badge-soft-danger">{{ translate('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('vendor.category.discounts.edit', $d->id) }}">{{ translate('Edit') }}</a>
                                            <form class="d-inline" method="POST" action="{{ route('vendor.category.discounts.destroy', $d->id) }}" onsubmit="return confirm('{{ translate('Are you sure?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">{{ translate('Delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">{{ translate('No discounts yet') }}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(($discounts ?? null) && $discounts->hasPages())
                        <div class="card-footer">
                            {!! $discounts->links() !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

