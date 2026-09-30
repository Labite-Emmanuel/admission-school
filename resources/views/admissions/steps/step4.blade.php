@php
    $step4info = $step4info ?? (object) [];
    $step2info = $step2info ?? (object) [];
@endphp
<style>
    .form-control.form-control-saved,
    .form-control.form-control-saved:focus {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.06);
    }
    select.form-control.form-control-saved option { color: #212529; }
    select.form-control.form-control-saved { color: #0d6efd; }
    .info-block { border: 1px solid #dee2e6; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1rem; background: #f8f9fa; }
    .info-block-title { font-weight: 600; color: #212529; margin-bottom: 0.75rem; font-size: 0.95rem; }
</style>
<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex align-items-center">
            <span class="bg-white avatar avatar-sm me-2 text-gray-7 flex-shrink-0">
                <i class="ti ti-info-square-rounded fs-16"></i>
            </span>
            <h4 class="text-dark">Step 3 - EMERGENCY AND AUTHORISE PERSONNE</h4>
        </div>
    </div>
    <div class="card-body pb-1">
        <input type="hidden" name="code_student" class="code_student">
        <input type="hidden" name="id_us" value="{{ session('user.id') }}">

        <!-- Emergency Contacts -->
        <div class="info-block mb-4">
            <div class="info-block-title">Emergency Contacts</div>
            <div class="row row-cols-xxl-3 row-cols-md-6">
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Emergency Name 1 <span style="color: red;">*</span></label>
                        <input type="text" name="emergency_name1" value="{{ $step2info->emergency_name1 ?? '' }}" class="form-control @if(!empty($step2info->emergency_name1)) form-control-saved @endif" required>
                    </div>
                </div>
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Emergency Contact 1 <span style="color: red;">*</span></label>
                        <input type="text" name="emergency_contact1" value="{{ $step2info->emergency_contact1 ?? '' }}" class="form-control @if(!empty($step2info->emergency_contact1)) form-control-saved @endif" placeholder="+22500000000" required>
                    </div>
                </div>
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Emergency Relation 1 <span style="color: red;">*</span></label>
                        <select name="emergency_relation1" class="form-control @if(!empty($step2info->emergency_relation1)) form-control-saved @endif" required>
                            <option value="">Select relation</option>
                            @foreach($relations as $relation)
                            <option value="{{ $relation->description }}" @if(isset($step2info->emergency_relation1) && $step2info->emergency_relation1 == $relation->description) selected @endif>{{ $relation->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Emergency Name 2</label>
                        <input type="text" name="emergency_name2" value="{{ $step2info->emergency_name2 ?? '' }}" class="form-control">
                    </div>
                </div>
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Emergency Contact 2</label>
                        <input type="text" name="emergency_contact2" value="{{ $step2info->emergency_contact2 ?? '' }}" class="form-control" placeholder="+22500000000">
                    </div>
                </div>
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Emergency Relation 2</label>
                        <select name="emergency_relation2" class="form-control">
                            <option value="">Select relation</option>
                            @foreach($relations as $relation)
                            <option value="{{ $relation->description }}" @if(isset($step2info->emergency_relation2) && $step2info->emergency_relation2 == $relation->description) selected @endif>{{ $relation->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-block-title mb-2">Authorized Pickup</div>
        <div class="row row-cols-xxl-3 row-cols-md-6">
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Name 1 <span style="color: red;">*</span></label>
                    <input type="text" name="pickup_name1" value="{{ $step4info->pickup_name1 ?? '' }}" class="form-control @if(!empty($step4info->pickup_name1)) form-control-saved @endif" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Contact 1 <span style="color: red;">*</span></label>
                    <input type="text" name="pickup_contact1" value="{{ $step4info->pickup_contact1 ?? '' }}" class="form-control @if(!empty($step4info->pickup_contact1)) form-control-saved @endif" placeholder="+22500000000" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Relation 1 <span style="color: red;">*</span></label>
                    <select name="pickup_relation1" class="form-control @if(!empty($step4info->pickup_relation1)) form-control-saved @endif" required>
                        <option value="">Select relation</option>
                        @foreach($relations as $relation)
                        <option value="{{ $relation->description }}" @if(isset($step4info->pickup_relation1) && $step4info->pickup_relation1 == $relation->description) selected @endif>{{ $relation->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xxl col-xl-4 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Photo 1</label>
                    <input type="file" name="pickup_photo1" class="form-control" accept=".pdf,.png,.jpg,.jpeg" @if(empty($step4info->filepickup1)) required @endif>
                    <p class="small text-muted mt-1 mb-0">Types : pdf, png, jpg, jpeg — Taille max : 5 Mo</p>
                    @if(!empty($step4info->filepickup1))
                    <div class="mt-1 small text-muted">
                        <i class="ti ti-file me-1"></i>
                        <a href="{{ asset(str_starts_with($step4info->filepickup1 ?? '', 'uploads/') ? $step4info->filepickup1 : 'storage/' . $step4info->filepickup1) }}" target="_blank" rel="noopener">{{ basename($step4info->filepickup1) }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row row-cols-xxl-3 row-cols-md-6">
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Name 2 </label>
                    <input type="text" name="pickup_name2" value="{{ $step4info->pickup_name2 ?? '' }}" class="form-control @if(!empty($step4info->pickup_name2)) form-control-saved @endif" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Contact 2  </label>
                    <input type="text" name="pickup_contact2" value="{{ $step4info->pickup_contact2 ?? '' }}" class="form-control @if(!empty($step4info->pickup_contact2)) form-control-saved @endif" placeholder="+22500000000" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Relation 2 </label>
                    <select name="pickup_relation2" class="form-control @if(!empty($step4info->pickup_relation2)) form-control-saved @endif" required>
                        <option value="">Select relation</option>
                        @foreach($relations as $relation)
                        <option value="{{ $relation->description }}" @if(isset($step4info->pickup_relation2) && $step4info->pickup_relation2 == $relation->description) selected @endif>{{ $relation->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xxl col-xl-4 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Photo 2</label>
                    <input type="file" name="pickup_photo2" class="form-control" accept=".pdf,.png,.jpg,.jpeg" @if(empty($step4info->filepickup2)) required @endif>
                    <p class="small text-muted mt-1 mb-0">Types : pdf, png, jpg, jpeg — Taille max : 5 Mo</p>
                    @if(!empty($step4info->filepickup2))
                    <div class="mt-1 small text-muted">
                        <i class="ti ti-file me-1"></i>
                        <a href="{{ asset(str_starts_with($step4info->filepickup2 ?? '', 'uploads/') ? $step4info->filepickup2 : 'storage/' . $step4info->filepickup2) }}" target="_blank" rel="noopener">{{ basename($step4info->filepickup2) }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row row-cols-xxl-3 row-cols-md-6">
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Name 3</label>
                    <input type="text" name="pickup_name3" value="{{ $step4info->pickup_name3 ?? '' }}" class="form-control @if(!empty($step4info->pickup_name3)) form-control-saved @endif" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Contact 3</label>
                    <input type="text" name="pickup_contact3" value="{{ $step4info->pickup_contact3 ?? '' }}" class="form-control @if(!empty($step4info->pickup_contact3)) form-control-saved @endif" placeholder="+22500000000" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Relation 3</label>
                    <select name="pickup_relation3" class="form-control @if(!empty($step4info->pickup_relation3)) form-control-saved @endif" required>
                        <option value="">Select relation</option>
                        @foreach($relations as $relation)
                        <option value="{{ $relation->description }}" @if(isset($step4info->pickup_relation3) && $step4info->pickup_relation3 == $relation->description) selected @endif>{{ $relation->description }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xxl col-xl-4 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Pickup Photo 3</label>
                    <input type="file" name="pickup_photo3" class="form-control" accept=".pdf,.png,.jpg,.jpeg" @if(empty($step4info->filepickup3)) required @endif>
                    <p class="small text-muted mt-1 mb-0">Types : pdf, png, jpg, jpeg — Taille max : 5 Mo</p>
                    @if(!empty($step4info->filepickup3))
                    <div class="mt-1 small text-muted">
                        <i class="ti ti-file me-1"></i>
                        <a href="{{ asset(str_starts_with($step4info->filepickup3 ?? '', 'uploads/') ? $step4info->filepickup3 : 'storage/' . $step4info->filepickup3) }}" target="_blank" rel="noopener">{{ basename($step4info->filepickup3) }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <p class="text-muted small mb-0 mt-2 text-end">Document format: <span class="text-danger">pdf, png, jpg, jpeg</span> (max 5MB)</p>
    </div>
</div>
