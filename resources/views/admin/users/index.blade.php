<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.head')

    <style>
    /* Modal Transparent White Background */
    .modal-content {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(8px);
        border-radius: 12px;
        border: none;
    }

    /* Vertical ID Card Styles */
    .id-card {
        width: 250px;
        height: 380px;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin: auto;
    }

    .front {
        background: linear-gradient(180deg, #0d6efd, #6610f2);
        color: white;
        text-align: center;
    }

    .back {
        background: #f8f9fa;
        color: #333;
        font-size: 14px;
    }

    .photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin: 10px auto;
    }

    .barcode {
        margin-top: 15px;
        text-align: center;
        background: white;
        padding: 5px;
        size: 12em;
    }

    h3, h4, p {
        margin: 5px 0;
    }
</style>
</head>

<body>

   <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed page-header-modern main-content-boxed">
        @include('layouts.header')
        @include('layouts.partials.sidebar')

        <div class="container mt-7">
            <h1 class="mb-4">User Management & Access</h1>
            <div class="table-responsive mb-6">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Staff ID</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Roles</th>
                            {{-- <th>Permissions</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->staff_id }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ optional($user->department)->name ?? 'N/A' }}</td>
                                <td>{{ $user->roles->pluck('name')->join(', ') ?: 'N/A' }}</td>
                                {{-- <td>
                                    @forelse($user->permissions as $perm)
                                        <span class="badge bg-info mb-1">{{ $perm->name }}</span>
                                    @empty
                                        <span class="text-muted">No permissions</span>
                                    @endforelse
                                </td> --}}
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editModal-{{ $user->id }}">Edit</a></li>
                                            {{-- <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#permissionModal-{{ $user->id }}">Permissions</a>
                                            </li> --}}
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#printModal-{{ $user->id }}">Print ID</a></li>
                                            <li>
                                                <form action="{{ route('admin.destroy_user', $user->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal-{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.update_user', $user->id) }}"
                                                    method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header bg-secondary text-white">
                                                        <h5 class="modal-title">Edit User: {{ $user->name }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <label>Name</label>
                                                                <input type="text" name="name"
                                                                    class="form-control" value="{{ $user->name }}"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Email</label>
                                                                <input type="email" name="email"
                                                                    class="form-control" value="{{ $user->email }}"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Phone</label>
                                                                <input type="text" name="phone"
                                                                    class="form-control" value="{{ $user->phone }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Department</label>
                                                                <select name="department_id" class="form-select">
                                                                    <option value="">--Select--</option>
                                                                    @foreach ($departments as $dept)
                                                                        <option value="{{ $dept->id }}"
                                                                            {{ $user->department_id == $dept->id ? 'selected' : '' }}>
                                                                            {{ $dept->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Save
                                                            Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Permissions Modal -->
                                    {{-- <div class="modal fade" id="permissionModal-{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('access.givePermission', $user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">Edit Permissions: {{ $user->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @foreach ($permissions as $perm)
                                                                <div class="col-6 col-md-4">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" name="permissions[]"
                                                                            value="{{ $perm->name }}"
                                                                            {{ $user->permissions->contains('name', $perm->name) ? 'checked' : '' }}>
                                                                        <label>{{ $perm->name }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div> --}}


                                    <!-- Print ID Modal -->
<div class="modal fade" id="printModal-{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
       <div class="sheet">

    <!-- ================= FRONT ================= -->
    <section class="id-card front">
      <div class="slot"></div>
      <header class="strap">
        <div class="brand-row">
          <!-- Replace this with your logo image if available -->
          <!-- <img src="{{ asset('images/logo.png') }}" class="logo-img" alt="Logo"> -->
          <div class="logo">WG</div>
          <div class="brand-text">
            <div class="brand-name">Waltergates Ghana Limited</div>
            <div class="brand-sub">STAFF ID</div>
          </div>
        </div>
        <div class="label">AUTHORIZED PERSONNEL</div>
      </header>

      <div class="body">
        <div class="avatar">
          <!-- Staff photo -->
          <img src="{{ $user->photo_url ?? asset('images/sample-avatar.jpg') }}" alt="Photo">
        </div>

        <div class="name">{{ strtoupper($user->name ?? 'JOHN DOE') }}</div>
        <div class="role">{{ $user->roles->pluck('name')->join(', ') }}</div>

        <div class="pair" style="margin-top:2mm">
          <div class="k">Staff ID</div><div class="v">{{ $user->staff_id ?? 'WG-000123' }}</div>
          <div class="k">Department</div><div class="v">{{ $dept }}</div>
          <div class="k">Phone</div><div class="v">{{ $user->phone ?? '+233 24 000 0000' }}</div>
        </div>

        <!-- If you generate a barcode or QR, place it here -->
        <div class="barcode">
          <!-- Example (image saved by your app): storage/barcodes/WG-000123.png -->
          @if(!empty($user->barcode_path))
            <img src="{{ asset($user->barcode_path) }}" alt="Barcode" style="max-height:100%">
          @else
            BARCODE / QR GOES HERE
          @endif
        </div>

        <div class="footer-note">If found, please return to Waltergates Ghana Limited.</div>
      </div>
    </section>

    <!-- ================= BACK ================= -->
    <section class="id-card back">
      <div class="slot"></div>

      <header class="strap">
        <div class="brand-row">
          <div class="logo">WG</div>
          <div class="brand-text">
            <div class="brand-name">Waltergates Ghana Limited</div>
            <div class="brand-sub">IDENTITY CARD • BACK</div>
          </div>
        </div>
      </header>

      <div class="body back-body" style="display:flex; flex-direction:column;">
        <!-- Mag stripe aesthetic -->
        <div class="strip"></div>

        <div class="info">
          <div class="row"><div class="k">Issued</div><div class="v">{{ optional($user->issued_at ?? null)->format('d M Y') ?? '01 Jan 2025' }}</div></div>
          <div class="row"><div class="k">Expiry</div><div class="v">{{ optional($user->expires_at ?? null)->format('d M Y') ?? '31 Dec 2026' }}</div></div>
          <div class="row"><div class="k">Emergency</div><div class="v">{{ $user->emergency_contact_phone ?? '+233 20 000 0000' }}</div></div>
          <div class="row"><div class="k">Address</div><div class="v">{{ $user->address ?? 'Accra, Ghana' }}</div></div>
        </div>

        <div class="policy">
          This card is the property of Waltergates Ghana Limited. If found, kindly return it to HR.
          The bearer must present this ID upon request while on company premises.
        </div>

        <div class="codes">
          <div class="qrcode">
            @if(!empty($user->qrcode_path))
              <img src="{{ asset($user->qrcode_path) }}" alt="QR Code" style="max-height:100%">
            @else
              QR CODE
            @endif
          </div>
          <div class="qrcode">
            NFC / Hologram / Seal
          </div>
        </div>

        <div class="sign-block">
          <div class="sign-line"></div>
          <div class="sign-caption">Authorized Signature</div>
        </div>
      </div>
    </section>
  </div>
    </div>
</div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @include('layouts.js')

        </div>
    </div>


    <!-- Bootstrap JS -->
</body>

</html>
