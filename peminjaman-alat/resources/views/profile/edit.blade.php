{{-- File ini adalah fallback. Untuk edit profile, gunakan route profile.edit yang akan mengarahkan ke view yang sesuai dengan role --}}
@if(auth()->user()->isAdmin())
    @include('admin.profile-edit')
@elseif(auth()->user()->isPetugas())
    @include('petugas.profile-edit')
@else
    @include('peminjam.profile-edit')
@endif
