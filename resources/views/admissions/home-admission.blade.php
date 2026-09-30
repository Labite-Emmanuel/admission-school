<?php $page = 'home-admission'; ?>
@extends('layout.mainlayout')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="col-lg-12">
            <div class="row align-items-center p-4">
                <div class="col-md-6">
                    <h3 class="page-title mb-1"><i class="ti ti-list-check me-2"></i>Admission List</h3>
                    <p class="mb-0 text-muted">Manage and track student admissions for {{ $acayear->year ?? 'Current Year' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ url('admission?step=1&new=1') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>New admission
                    </a>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success mx-4" role="alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mx-4" role="alert">{{ session('error') }}</div>
        @endif
        <!-- /Page Header -->

        <!-- Content Container -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0 bg-light">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-school me-2"></i>My admissions ({{ count($admilists ?? []) }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th><strong>N°</strong></th>
                                        <th><strong>Photo</strong></th>
                                        <th><strong>First Name</strong></th>
                                        <th><strong>Last Name</strong></th>
                                        <th><strong>Current Class</strong></th>
                                        <th><strong>Requested Class</strong></th>
                                        <th><strong>Level</strong></th>
                                        <th><strong>Academic Year</strong></th>
                                        <th><strong>Status</strong></th>
                                        <th><strong>Progress</strong></th>
                                        <th><strong>Actions</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admilists as $admilist)
                                        @php
                                            $reg = $admilist->registration ?? 'in_process';
                                            $stepRaw = $admilist->step ?? 'step1';
                                            $stepNum = is_numeric($stepRaw) ? (int) $stepRaw : ((int) preg_replace('/^step/i', '', $stepRaw) ?: 1);
                                            $totalSteps = 6;
                                            $progress = min(100, max(0, (int) round(($stepNum / $totalSteps) * 100)));
                                        @endphp
                                        <tr>
                                            <td><span class="badge bg-secondary">{{ $loop->iteration }}</span></td>
                                            <td>
                                                @php
                                                    $photoPath = $admilist->file ?? null;
                                                    $isImage = $photoPath && in_array(strtolower(pathinfo($photoPath, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg']);
                                                @endphp
                                                @if($isImage)
                                                    <img src="{{ asset(str_starts_with($photoPath ?? '', 'uploads/') ? $photoPath : 'storage/' . $photoPath) }}" alt="{{ $admilist->prenom ?? 'Photo' }}" class="avatar avatar-sm rounded-circle object-fit-cover" style="width: 40px; height: 40px;">
                                                @else
                                                    <span class="avatar avatar-sm bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-semibold">
                                                        {{ strtoupper(substr($admilist->prenom ?? 'S', 0, 1)) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $admilist->prenom ?? 'N/A' }}</strong></td>
                                            <td>{{ $admilist->nom ?? 'N/A' }}</td>
                                            <td>{{ $admilist->classroom ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $admilist->class_required ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $admilist->class_level ?? 'N/A' }}</td>
                                            <td>{{ $acayear->year ?? 'N/A' }}</td>
                                            <td>
                                                @if($reg === 'in_process')
                                                    <span class="badge bg-danger text-white">Not Completed</span>
                                                @elseif($reg === 'process_end')
                                                    <span class="badge bg-warning">Waiting for validation</span>
                                                @elseif($reg === 'pending')
                                                    <span class="badge bg-info">Pending Review</span>
                                                @elseif($reg === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $reg }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress flex-grow-1" style="height: 24px; min-width: 80px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                         style="width: {{ $progress }}%"
                                                         aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                                        {{ $progress }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($reg === 'in_process')
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <a href="{{ url('admission?code_student=' . ($admilist->code_student ?? '') . '&step=' . $stepNum) }}"
                                                           class="btn btn-primary btn-sm"
                                                           title="Continue or edit your admission">
                                                            <i class="ti ti-edit me-1"></i>Edit
                                                        </a>
                                                        <button type="button"
                                                                class="btn btn-outline-danger btn-sm js-open-delete-admission-modal"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteAdmissionModal"
                                                                data-delete-url="{{ route('delete-admission', $admilist->code_student ?? '') }}"
                                                                data-student-name="{{ trim(($admilist->prenom ?? '') . ' ' . ($admilist->nom ?? '')) ?: 'N/A' }}"
                                                                data-code-student="{{ $admilist->code_student ?? 'N/A' }}"
                                                                title="Delete this in-process admission">
                                                            <i class="ti ti-trash me-1"></i>Delete
                                                        </button>
                                                    </div>
                                                @elseif($reg === 'process_end')
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <a href="{{ url('admission?code_student=' . ($admilist->code_student ?? '') . '&step=6') }}"
                                                           class="btn btn-outline-primary btn-sm"
                                                           title="Modify or view your admission before validation">
                                                            <i class="ti ti-eye me-1"></i>Edit / View
                                                        </a>
                                                        <a href="{{ url('duplicate-admission?code_student=' . ($admilist->code_student ?? '')) }}"
                                                           class="btn btn-outline-secondary btn-sm"
                                                           title="Duplicate this admission (copy all data)">
                                                            <i class="ti ti-copy me-1"></i>Duplicate
                                                        </a>
                                                    </div>
                                                @elseif($reg === 'pending')
                                                    <a href="{{ url('admission?code_student=' . ($admilist->code_student ?? '') . '&step=6') }}"
                                                       class="btn btn-outline-secondary btn-sm"
                                                       title="View admission information">
                                                        <i class="ti ti-eye me-1"></i>View
                                                    </a>
                                                @elseif($reg === 'rejected')
                                                    <a href="{{ url('admission-redirect?code_student=' . ($admilist->code_student ?? '')) }}"
                                                       class="btn btn-outline-danger btn-sm">
                                                        <i class="ti ti-refresh me-1"></i>Update
                                                    </a>
                                                @else
                                                    <a href="{{ url('admission?code_student=' . ($admilist->code_student ?? '') . '&step=6') }}"
                                                       class="btn btn-outline-secondary btn-sm">
                                                        <i class="ti ti-eye me-1"></i>View
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="ti ti-inbox fs-1 opacity-50 d-block mb-2"></i>
                                                    <p class="mb-0">No admissions for this year</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats: registration counts by status (per id_userS) -->
        <!-- <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="avatar avatar-lg rounded-circle bg-warning mb-2 d-inline-flex align-items-center justify-content-center text-dark">
                            <i class="ti ti-clock fs-2"></i>
                        </span>
                        <h5 class="card-title mb-1">In Process</h5>
                        <p class="mb-0 fw-bold fs-4 text-warning">{{ $count_in_process ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="avatar avatar-lg rounded-circle bg-success mb-2 d-inline-flex align-items-center justify-content-center text-white">
                            <i class="ti ti-circle-check fs-2"></i>
                        </span>
                        <h5 class="card-title mb-1">Completed</h5>
                        <p class="mb-0 fw-bold fs-4 text-success">{{ $count_completed ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="avatar avatar-lg rounded-circle bg-info mb-2 d-inline-flex align-items-center justify-content-center text-white">
                            <i class="ti ti-hourglass-high fs-2"></i>
                        </span>
                        <h5 class="card-title mb-1">Pending Review</h5>
                        <p class="mb-0 fw-bold fs-4 text-info">{{ $count_pending ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <span class="avatar avatar-lg rounded-circle bg-danger mb-2 d-inline-flex align-items-center justify-content-center text-white">
                            <i class="ti ti-circle-x fs-2"></i>
                        </span>
                        <h5 class="card-title mb-1">Rejected</h5>
                        <p class="mb-0 fw-bold fs-4 text-danger">{{ $count_rejected ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>

<div class="modal fade" id="deleteAdmissionModal" tabindex="-1" aria-labelledby="deleteAdmissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title text-danger" id="deleteAdmissionModalLabel">
                        <i class="ti ti-alert-triangle me-1"></i>Supprimer l'admission
                    </h5>
                    <p class="mb-0 text-muted small">Cette action est definitive.</p>
                </div>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-2">
                    Vous etes sur le point de supprimer l'admission en cours de
                    <strong id="deleteAdmissionStudentName">cet eleve</strong>.
                </p>
                <div class="alert alert-danger mb-0" role="alert">
                    Toutes les informations deja saisies pour cette admission seront supprimees dans les tables
                    d'admission : details de l'eleve, annee academique, fichiers, informations medicales,
                    contacts d'urgence, autorisations de recuperation, engagements, documents et informations
                    learning/behavioural. Cette action ne peut pas etre annulee.
                </div>
                <p class="text-muted small mt-2 mb-0">
                    Code eleve : <span id="deleteAdmissionStudentCode">N/A</span>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteAdmissionForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Oui, supprimer definitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteForm = document.getElementById('deleteAdmissionForm');
        var studentName = document.getElementById('deleteAdmissionStudentName');
        var studentCode = document.getElementById('deleteAdmissionStudentCode');

        document.querySelectorAll('.js-open-delete-admission-modal').forEach(function (button) {
            button.addEventListener('click', function () {
                deleteForm.action = button.dataset.deleteUrl || '';
                studentName.textContent = button.dataset.studentName || 'cet eleve';
                studentCode.textContent = button.dataset.codeStudent || 'N/A';
            });
        });
    });
</script>
<!-- /Page Wrapper -->
@endsection
