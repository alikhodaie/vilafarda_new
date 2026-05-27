@props(['user'])

@can('update', $user)
    @php $canEditUser = true; @endphp
@else
    @php $canEditUser = false; @endphp
@endcan

<button type="button"
        class="btn btn-link btn-sm p-0 text-500 admin-user-contact-trigger"
        data-bs-toggle="modal"
        data-bs-target="#adminUserContactModal"
        data-user-name="{{ $user->full_name }}"
        data-user-mobile="{{ $user->mobile ?? '' }}"
        data-user-email="{{ $user->email ?? '' }}"
        data-user-id="{{ $user->id }}"
        @if($canEditUser)
        data-user-edit-url="{{ route('admin.users.edit', $user) }}"
        @endif
        title="اطلاعات تماس"
        aria-label="اطلاعات تماس {{ $user->full_name }}">
    <span class="fas fa-address-card"></span>
</button>
