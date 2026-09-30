@php
    $codeStudent = $step1info->code_student ?? session('studData.codeStudent') ?? old('code_student') ?? '';
@endphp

<style>
    .form-control.form-control-saved,
    .form-control.form-control-saved:focus {
        color: #0d6efd;
        border-color: #0d6efd;
    }
    select.form-control.form-control-saved option { color: #212529; }
    select.form-control.form-control-saved { color: #0d6efd; }
</style>

<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex align-items-center">
            <span class="bg-white avatar avatar-sm me-2 text-gray-7 flex-shrink-0">
                <i class="ti ti-info-square-rounded fs-16"></i>
            </span>
            <h4 class="text-dark">Step 5 - FAMILY INFORMATION</h4>
        </div>
    </div>
    <div id="step5-validation-errors" class="alert alert-danger d-none mx-3 mt-2" role="alert"></div>
    <div class="card-body pb-1">
        <p class="text-muted text-danger text-2xl mb-3">You must provide at least one parent (Father or Mother). The Guardian is optional.</p>
        <div class="row row-cols-xxl-3 row-cols-md-6">
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <button type="button" class="form-control btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_father">Father</button>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <button type="button" class="form-control btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_mother">Mother</button>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <button type="button" class="form-control btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_guardian">Guardian</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body pb-1">
        <div class="row row-cols-xxl-3 row-cols-md-6">
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <div class="small text-muted">Father</div>
                    <div id="namefather" class="{{ $step4father ? 'text-primary fw-medium' : 'text-muted' }}">
                        @if($step4father ?? null)
                            {{ trim(($step4father->fist_name ?? '') . ' ' . ($step4father->last_name ?? '')) }}
                        @else
                            No data — add parent above
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <div class="small text-muted">Mother</div>
                    <div id="namemother" class="{{ $step4mother ?? null ? 'text-primary fw-medium' : 'text-muted' }}">
                        @if($step4mother ?? null)
                            {{ trim(($step4mother->fist_name ?? '') . ' ' . ($step4mother->last_name ?? '')) }}
                        @else
                            No data — add parent above
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <div class="small text-muted">Guardian</div>
                    <div id="nameguardien" class="{{ $step4guardian ?? null ? 'text-primary fw-medium' : 'text-muted' }}">
                        @if($step4guardian ?? null)
                            {{ trim(($step4guardian->fist_name ?? '') . ' ' . ($step4guardian->last_name ?? '')) }}
                        @else
                            No data — add parent above
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_father">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Father</h4>
                    <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <!-- <form action="{{url('post_info_step5')}}" method='post'>
                    @csrf -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">The child already has a Father registered in our database</label>
                                    <select name="" class="form-control" id="searchfather" onchange="toggleSearchfather()">
                                        <option value="">Select Yes or No</option>
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <form id="form-step5-link-father" action="{{ url('update_student_parent') }}" method="post">
                            @csrf
                            <input type="hidden" name="code_student" class="code_student" value="{{ $codeStudent }}">
                            <input type="hidden" name="father_id" id="parentfather_id" value="">
                            <input type="hidden" name="person" value="Father">
                            <div id="search_father" hidden>
                                <div class="nav-search position-relative">
                                    <label class="form-label">Search Parents</label>
                                    <input type="text" id="name_father_input" class="form-control" placeholder="Type name or email (min 2 characters)..." autocomplete="off">
                                    <div id="parent_results_father" class="list-group position-absolute w-100 mt-1 border rounded shadow-sm" style="max-height: 220px; overflow-y: auto; display: none; z-index: 1050;"></div>
                                    <div id="parent_selected_father" class="mt-2 p-2 bg-light rounded small" style="display: none;">
                                        <strong>Selected:</strong> <span id="parent_selected_name_father"></span>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="clearParentSelection('father')">Clear</button>
                                    </div>
                                </div>
                                <div class="modal-footer mt-3">
                                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                                    <button type="submit" class="btn btn-danger" id="btn_save_father" disabled>Associate Parent</button>
                                </div>
                            </div>
                        </form>

                        <form id="form-step5-add-father" action="{{ url('post_info_step5') }}" method="post">
                            @csrf
                            <input type="hidden" name="code_student" class="code_student" value="{{ $codeStudent }}">
                            <input type="hidden" name="id_us" value="{{ session('user.id') }}">
                            <input type="hidden" name="civility" value="Mr">
                            <input type="hidden" name="person" value="Father">
                            <div id="detailfather" hidden>
                                <div class="row row-cols-xxl-3 row-cols-md-6" >
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Civil Title</label>
                                            <input type="text" value="Mr" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Person</label>
                                            <input type="text" value="Father" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', optional($step4father)->last_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', optional($step4father)->fist_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Main Mobile <span class="text-danger">*</span></label>
                                            <input type="text" name="main_mobile" class="form-control" placeholder="+22500000000" value="{{ old('main_mobile', optional($step4father)->main_mobile) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email 1 <span class="text-danger">*</span></label>
                                            <input type="email" name="email_1" class="form-control" value="{{ old('email_1', optional($step4father)->email) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email 2</label>
                                            <input type="email" name="email_2" class="form-control" value="{{ old('email_2', optional($step4father)->email2) }}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" placeholder="+22500000000" value="{{ old('phone', optional($step4father)->phone) }}" required>
                                        </div>
                                    </div> -->
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Home Phone</label>
                                            <input type="text" name="home_phone" class="form-control" placeholder="+22500000000" value="{{ old('home_phone', optional($step4father)->home_phone) }}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Personal Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="personal_phone" class="form-control" placeholder="+22500000000" value="{{ old('personal_phone', optional($step4father)->personnal_phone) }}" required>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Other Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="other_phone" class="form-control" placeholder="+22500000000" value="{{ old('other_phone', optional($step4father)->other_phone) }}" required>
                                        </div>
                                    </div> -->
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">WhatsApp Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="whatsapp_phone" class="form-control" placeholder="+22500000000" value="{{ old('whatsapp_phone', optional($step4father)->whatsapp_phone) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Work Phone </label>
                                            <input type="text" name="work_phone" class="form-control" placeholder="+22500000000" value="{{ old('work_phone', optional($step4father)->work_phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Other Mobile</label>
                                            <input type="text" name="other_mobile" class="form-control" value="{{ old('other_mobile', optional($step4father)->other_mobile) }}">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xxl col-xl-12 col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                                <textarea name="adress" id="adress" class="form-control" rows="3" cols="30" required>{{ old('adress', optional($step4father)->adress) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', optional($step4father)->postal_code) }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city', optional($step4father)->city) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                            <select name="country" class="form-control" required>
                                                <option value="">Select country</option>
                                                @foreach($countries ?? [] as $country)
                                                    <option value="{{ $country->nom_en_gb }}" {{ old('country', optional($step4father)->country) == $country->nom_en_gb || old('country', optional($step4father)->country) == $country->nom_fr_fr ? 'selected' : '' }}>{{ $country->nom_fr_fr ?? $country->nom_en_gb }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                            <select name="nationality" class="form-control" required>
                                                <option value="">Select nationality</option>
                                                @foreach($national ?? [] as $nationals)
                                                    <option value="{{ $nationals->name_nationality }}" {{ old('nationality', optional($step4father)->nationality) == $nationals->name_nationality ? 'selected' : '' }}>{{ $nationals->name_nationality }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">SMS/Email Language <span class="text-danger">*</span></label>
                                            <select name="main_language" class="form-control" required>
                                                <option value="french" {{ old('main_language', optional($step4father)->main_language) == 'french' ? 'selected' : '' }}>French</option>
                                                <option value="english" {{ old('main_language', optional($step4father)->main_language) == 'english' ? 'selected' : '' }}>English</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Occupation <span class="text-danger">*</span></label>
                                            <input type="text" name="occupation" class="form-control" value="{{ old('occupation', optional($step4father)->occupation) }}" required>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Enterprise</label>
                                            <input type="text" name="enterprise" class="form-control" value="{{ old('enterprise', optional($step4father)->enterprise) }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Responsible of school fees <span class="text-danger">*</span></label>
                                            <select name="responsible" class="form-control" required>
                                                <option value="No" {{ in_array(old('responsible', optional($step4father)->responsible_of_school_fees), ['No', 'no'], true) ? 'selected' : '' }}>No</option>
                                                <option value="Yes" {{ in_array(old('responsible', optional($step4father)->responsible_of_school_fees), ['Yes', 'yes'], true) ? 'selected' : '' }}>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Enterprise address</label>
                                            <textarea name="enterprise_addres" id="enterprise_addres" class="form-control" rows="3" cols="30">{{ old('enterprise_addres', optional($step4father)->enterprise_adress) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_mother">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Mother</h4>
                    <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <!-- <form action="{{url('post_info_step5')}}" method='post'>
                    @csrf -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">The child already has a Mother registered in our database</label>
                                    <select name="" class="form-control" id="searchmother" onchange="toggleSearchmother()">
                                        <option value="">Select Yes or No</option>
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <form id="form-step5-link-mother" action="{{ url('update_student_parent') }}" method="post">
                            @csrf
                            <input type="hidden" name="code_student" class="code_student" value="{{ $codeStudent }}">
                            <input type="hidden" name="mother_id" id="parentmother_id" value="">
                            <input type="hidden" name="person" value="Mother">
                            <div id="search_mother" hidden>
                                <div class="nav-search position-relative">
                                    <label class="form-label">Search Parents</label>
                                    <input type="text" id="name_mother_input" class="form-control" placeholder="Type name or email (min 2 characters)..." autocomplete="off">
                                    <div id="parent_results_mother" class="list-group position-absolute w-100 mt-1 border rounded shadow-sm" style="max-height: 220px; overflow-y: auto; display: none; z-index: 1050;"></div>
                                    <div id="parent_selected_mother" class="mt-2 p-2 bg-light rounded small" style="display: none;">
                                        <strong>Selected:</strong> <span id="parent_selected_name_mother"></span>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="clearParentSelection('mother')">Clear</button>
                                    </div>
                                </div>
                                <div class="modal-footer mt-3">
                                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btn_save_mother" disabled>Associate Parent</button>
                                </div>
                            </div>
                        </form>
                        <form id="form-step5-add-mother" action="{{ url('post_info_step5') }}" method="post">
                            @csrf
                            <input type="hidden" name="code_student" class="code_student" value="{{ $codeStudent }}">
                            <input type="hidden" name="id_us" value="{{ session('user.id') }}">
                            <input type="hidden" name="person" value="Mother">
                            <div id="detailmother" hidden>
                                <div class="row row-cols-xxl-3 row-cols-md-6">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Civil Title <span class="text-danger">*</span></label>
                                            <select name="civility" class="form-control" required>
                                                <option value="">Choose</option>
                                                <option value="Mrs" {{ old('civility', optional($step4mother)->civility) == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                                <option value="Ms" {{ old('civility', optional($step4mother)->civility) == 'Ms' ? 'selected' : '' }}>Ms</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Person</label>
                                            <input type="text" value="Mother" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', optional($step4mother)->last_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', optional($step4mother)->fist_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Main Mobile <span class="text-danger">*</span></label>
                                            <input type="text" name="main_mobile" class="form-control" placeholder="+22500000000" value="{{ old('main_mobile', optional($step4mother)->main_mobile) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email 1 <span class="text-danger">*</span></label>
                                            <input type="email" name="email_1" class="form-control" value="{{ old('email_1', optional($step4mother)->email) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email 2</label>
                                            <input type="email" name="email_2" class="form-control" value="{{ old('email_2', optional($step4mother)->email2) }}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" placeholder="+22500000000" value="{{ old('phone', optional($step4mother)->phone) }}" required>
                                        </div>
                                    </div> -->
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Home Phone</label>
                                            <input type="text" name="home_phone" class="form-control" placeholder="+22500000000" value="{{ old('home_phone', optional($step4mother)->home_phone) }}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Personal Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="personal_phone" class="form-control" placeholder="+22500000000" value="{{ old('personal_phone', optional($step4mother)->personnal_phone) }}" required>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Other Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="other_phone" class="form-control" placeholder="+22500000000" value="{{ old('other_phone', optional($step4mother)->other_phone) }}" required>
                                        </div>
                                    </div> -->
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">WhatsApp Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="whatsapp_phone" class="form-control" placeholder="+22500000000" value="{{ old('whatsapp_phone', optional($step4mother)->whatsapp_phone) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Work Phone </label>
                                            <input type="text" name="work_phone" class="form-control" placeholder="+22500000000" value="{{ old('work_phone', optional($step4mother)->work_phone) }}">
                                        </div>
                                    </div>

                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Other Mobile</label>
                                            <input type="text" name="other_mobile" class="form-control" value="{{ old('other_mobile', optional($step4mother)->other_mobile) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xxl col-xl-12 col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea name="adress" id="adress" class="form-control" rows="3" cols="30" required>{{ old('adress', optional($step4mother)->adress) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', optional($step4mother)->postal_code) }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city', optional($step4mother)->city) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                            <select name="country" class="form-control" required>
                                                <option value="">Select country</option>
                                                @foreach($countries ?? [] as $country)
                                                    <option value="{{ $country->nom_en_gb }}" {{ old('country', optional($step4mother)->country) == $country->nom_en_gb || old('country', optional($step4mother)->country) == $country->nom_fr_fr ? 'selected' : '' }}>{{ $country->nom_fr_fr ?? $country->nom_en_gb }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                            <select name="nationality" class="form-control" required>
                                                <option value="">Select nationality</option>
                                                @foreach($national ?? [] as $nationals)
                                                    <option value="{{ $nationals->name_nationality }}" {{ old('nationality', optional($step4mother)->nationality) == $nationals->name_nationality ? 'selected' : '' }}>{{ $nationals->name_nationality }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">SMS/Email Language <span class="text-danger">*</span></label>
                                            <select name="main_language" class="form-control" required>
                                                <option value="french" {{ old('main_language', optional($step4mother)->main_language) == 'french' ? 'selected' : '' }}>French</option>
                                                <option value="english" {{ old('main_language', optional($step4mother)->main_language) == 'english' ? 'selected' : '' }}>English</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Occupation <span class="text-danger">*</span></label>
                                            <input type="text" name="occupation" class="form-control" value="{{ old('occupation', optional($step4mother)->occupation) }}" required>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Enterprise </label>
                                            <input type="text" name="enterprise" class="form-control" value="{{ old('enterprise', optional($step4mother)->enterprise) }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Responsible of school fees <span class="text-danger">*</span></label>
                                            <select name="responsible" class="form-control" required>
                                                <option value="No" {{ old('responsible', optional($step4mother)->responsible_of_school_fees) == 'No' ? 'selected' : '' }}>No</option>
                                                <option value="Yes" {{ old('responsible', optional($step4mother)->responsible_of_school_fees) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Enterprise address</label>
                                            <textarea name="enterprise_addres" id="enterprise_addres" class="form-control" rows="3" cols="30">{{ old('enterprise_addres', optional($step4mother)->enterprise_adress) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="add_guardian">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Guardian</h4>
                    <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">The child already has a Guardian registered in our database</label>
                                    <select name="" class="form-control" id="searchguardian" onchange="toggleSearchguardian()">
                                        <option value="">Select Yes or No</option>
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <form id="form-step5-link-guardian" action="{{ url('update_student_parent') }}" method="post">
                            @csrf
                            <input type="hidden" name="code_student" class="code_student" value="{{ $codeStudent }}">
                            <input type="hidden" name="guardian_id" id="parentguardian_id" value="">
                            <input type="hidden" name="person" value="Guardian">
                            <div id="search_guardian" hidden>
                                <div class="nav-search position-relative">
                                    <label class="form-label">Search Parents</label>
                                    <input type="text" id="name_guardian_input" class="form-control" placeholder="Type name or email (min 2 characters)..." autocomplete="off">
                                    <div id="parent_results_guardian" class="list-group position-absolute w-100 mt-1 border rounded shadow-sm" style="max-height: 220px; overflow-y: auto; display: none; z-index: 1050;"></div>
                                    <div id="parent_selected_guardian" class="mt-2 p-2 bg-light rounded small" style="display: none;">
                                        <strong>Selected:</strong> <span id="parent_selected_name_guardian"></span>
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="clearParentSelection('guardian')">Clear</button>
                                    </div>
                                </div>
                                <div class="modal-footer mt-3">
                                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btn_save_guardian" disabled>Associate Parent</button>
                                </div>
                            </div>
                        </form>

                        <form id="form-step5-add-guardian" action="{{ url('post_info_step5') }}" method="post">
                            @csrf
                            <input type="hidden" name="code_student" class="code_student" value="{{ $codeStudent }}">
                            <input type="hidden" name="id_us" value="{{ session('user.id') }}">
                            <input type="hidden" name="person" value="Guardian">
                            <div id="detailguardian" hidden>
                                <div class="row row-cols-xxl-3 row-cols-md-6">
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Civil Title <span class="text-danger">*</span></label>
                                            <select name="civility" class="form-control" required>
                                                <option value="">Choose</option>
                                                <option value="Mr" {{ old('civility', optional($step4guardian)->civility) == 'Mr' ? 'selected' : '' }}>Mr</option>
                                                <option value="Mrs" {{ old('civility', optional($step4guardian)->civility) == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                                <option value="Ms" {{ old('civility', optional($step4guardian)->civility) == 'Ms' ? 'selected' : '' }}>Ms</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Person</label>
                                            <input type="text" value="Guardian" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', optional($step4guardian)->last_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', optional($step4guardian)->fist_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Main Mobile <span class="text-danger">*</span></label>
                                                <input type="text" name="main_mobile" class="form-control" placeholder="+22500000000" value="{{ old('main_mobile', optional($step4guardian)->main_mobile) }}" required>
                                            </div>
                                        </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email 1 <span class="text-danger">*</span></label>
                                            <input type="email" name="email_1" class="form-control" value="{{ old('email_1', optional($step4guardian)->email) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email 2</label>
                                            <input type="email" name="email_2" class="form-control" value="{{ old('email_2', optional($step4guardian)->email2) }}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" placeholder="+22500000000" value="{{ old('phone', optional($step4guardian)->phone) }}" required>
                                        </div>
                                    </div> -->
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Home Phone</label>
                                            <input type="text" name="home_phone" class="form-control" placeholder="+22500000000" value="{{ old('home_phone', optional($step4guardian)->home_phone) }}">
                                        </div>
                                    </div>
                                    <!-- <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Personal Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="personal_phone" class="form-control" placeholder="+22500000000" value="{{ old('personal_phone', optional($step4guardian)->personnal_phone) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xxl col-xl-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Other Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="other_phone" class="form-control" placeholder="+22500000000" value="{{ old('other_phone', optional($step4guardian)->other_phone) }}" required>
                                        </div>
                                    </div> -->
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">WhatsApp Phone <span class="text-danger">*</span></label>
                                                <input type="text" name="whatsapp_phone" class="form-control" placeholder="+22500000000" value="{{ old('whatsapp_phone', optional($step4guardian)->whatsapp_phone) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Work Phone </label>
                                                <input type="text" name="work_phone" class="form-control" placeholder="+22500000000" value="{{ old('work_phone', optional($step4guardian)->work_phone) }}">
                                            </div>
                                        </div>

                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Other Mobile</label>
                                                <input type="text" name="other_mobile" class="form-control" value="{{ old('other_mobile', optional($step4guardian)->other_mobile) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xxl col-xl-12 col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                                <textarea name="adress" id="adress" class="form-control" rows="3" cols="30" required>{{ old('adress', optional($step4guardian)->adress) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Postal Code</label>
                                                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', optional($step4guardian)->postal_code) }}">
                                            </div>
                                        </div>
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">City <span class="text-danger">*</span></label>
                                                <input type="text" name="city" class="form-control" value="{{ old('city', optional($step4guardian)->city) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-xxl col-xl-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                                    <select name="country" class="form-control" required>
                                                        <option value="">Select country</option>
                                                        @foreach($countries ?? [] as $country)
                                                            <option value="{{ $country->nom_en_gb }}" {{ old('country', optional($step4guardian)->country) == $country->nom_en_gb || old('country', optional($step4guardian)->country) == $country->nom_fr_fr ? 'selected' : '' }}>{{ $country->nom_fr_fr ?? $country->nom_en_gb }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xxl col-xl-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                                    <select name="nationality" class="form-control" required>
                                                        <option value="">Select nationality</option>
                                                        @foreach($national ?? [] as $nationals)
                                                            <option value="{{ $nationals->name_nationality }}" {{ old('nationality', optional($step4guardian)->nationality) == $nationals->name_nationality ? 'selected' : '' }}>{{ $nationals->name_nationality }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">SMS/Email Language <span class="text-danger">*</span></label>
                                                <select name="main_language" class="form-control" required>
                                                    <option value="french" {{ old('main_language', optional($step4guardian)->main_language) == 'french' ? 'selected' : '' }}>French</option>
                                                    <option value="english" {{ old('main_language', optional($step4guardian)->main_language) == 'english' ? 'selected' : '' }}>English</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
<label class="form-label">Occupation <span class="text-danger">*</span></label>
                                            <input type="text" name="occupation" class="form-control" value="{{ old('occupation', optional($step4guardian)->occupation) }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Enterprise </label>
                                                <input type="text" name="enterprise" class="form-control" value="{{ old('enterprise', optional($step4guardian)->enterprise) }}">
                                            </div>
                                        </div>
                                        <div class="col-xxl col-xl-3 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Responsible of school fees <span class="text-danger">*</span></label>
                                                <select name="responsible" class="form-control" required>
                                                    <option value="No" {{ old('responsible', optional($step4guardian)->responsible_of_school_fees) == 'No' ? 'selected' : '' }}>No</option>
                                                    <option value="Yes" {{ old('responsible', optional($step4guardian)->responsible_of_school_fees) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Enterprise address</label>
                                            <textarea name="enterprise_addres" id="enterprise_addres" class="form-control" rows="3" cols="30">{{ old('enterprise_addres', optional($step4guardian)->enterprise_adress) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>

                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>

</div>

<script>
        
    document.addEventListener('DOMContentLoaded', function() {
        const nameFInput = document.getElementById('name_father_input');
        const parentFIdInput = document.getElementById('parentfather_id');
        const dataFlist = document.getElementById('parentListFather');

        nameFInput.addEventListener('input', function() {
            // Trouver l'option correspondante dans le dataFlist
            const selectedFOption = Array.from(dataFlist.options).find(
                option => option.value === this.value
            );

            if (selectedFOption) {
                // Mettre ├á jour l'input hidden avec l'ID du parent
                parentFIdInput.value = selectedFOption.getAttribute('data-id');
				localStorage.setItem('father', selectedFOption.value);
                // console.log(localStorage.getItem('father'));
                // document.getElementById('namefather').textContent  = localStorage.getItem('father');
            } else {
                // R├®initialiser si aucune correspondance
                parentFIdInput.value = '';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const nameMInput = document.getElementById('name_mother_input');
        const parentMIdInput = document.getElementById('parentmother_id');
        const dataMlist = document.getElementById('parentListMother');

        nameMInput.addEventListener('input', function() {
            // Trouver l'option correspondante dans le dataMlist
            const selectedMOption = Array.from(dataMlist.options).find(
                option => option.value === this.value
            );

            if (selectedMOption) {
                // Mettre ├á jour l'input hidden avec l'ID du parent
                parentMIdInput.value = selectedMOption.getAttribute('data-id');
				localStorage.setItem('mother', selectedMOption.value);
                // document.getElementById('namemother').value = localStorage.getItem('mother');

            } else {
                // R├®initialiser si aucune correspondance
                parentMIdInput.value = '';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name_guardian_input');
        const parentIdInput = document.getElementById('parentguardian_id');
        const datalist = document.getElementById('parentListGuardian');

        nameInput.addEventListener('input', function() {
            // Trouver l'option correspondante dans le datalist
            const selectedOption = Array.from(datalist.options).find(
                option => option.value === this.value
            );

            if (selectedOption) {
                // Mettre ├á jour l'input hidden avec l'ID du parent
                parentIdInput.value = selectedOption.getAttribute('data-id');
				localStorage.setItem('guardian', selectedOption.value);
                // document.getElementById('nameguardien').value = localStorage.getItem('guardien');
            } else {
                // R├®initialiser si aucune correspondance
                parentIdInput.value = '';
            }
        });
    });

    // // document.addEventListener('DOMContentLoaded', function() {
    // function refreshparentData() {
    //     const fatherData = localStorage.getItem('father');
    //     const fatherDiv = document.getElementById('namefather');
    //     const motherData = localStorage.getItem('mother');
    //     const motherDiv = document.getElementById('namemother');
    //     const guardianData = localStorage.getItem('guardian');
    //     const guardianDiv = document.getElementById('nameguardien');
        
    //     if (fatherData) {
    //         fatherDiv.textContent = `Father Full Name : ${fatherData}`;

    //     } else {
    //         fatherDiv.textContent = 'Aucune information du p├¿re disponible';
    //         fatherDiv.style.color = '#999';
    //         fatherDiv.style.fontStyle = 'italic';
    //     }

    //     if (motherData) {
    //         motherDiv.textContent = `Mother Full Name : ${motherData}`;

    //     } else {

    //         motherDiv.textContent = 'Aucune information m├¿re disponible';
    //         motherDiv.style.color = '#999';
    //         motherDiv.style.fontStyle = 'italic';
    //     }

    //     if (guardianData) {
    //         guardianDiv.textContent = `Guardian Full Name : ${guardianData}`;

    //     } else {
    //         guardianDiv.textContent = 'Aucune information tuteur disponible';
    //         guardianDiv.style.color = '#999';
    //         guardianDiv.style.fontStyle = 'italic';
    //     }
    // }
    // // });
    


    function toggleSearchfather() {
        const search = document.getElementById('searchfather');
        const search_detail = document.getElementById('detailfather');
        const searfather = document.getElementById('search_father');
         
        if (search.value === 'no') {
            search_detail.hidden = false;
            searfather.hidden = true;

        }else if (search.value === 'yes') {
            search_detail.hidden = true;
            searfather.hidden = false;

        }else {
            search_detail.hidden = true;
            searfather.hidden = true;
        }
    }

    function toggleSearchmother() {
        const search = document.getElementById('searchmother');
        const search_detail = document.getElementById('detailmother');
        const searmother = document.getElementById('search_mother');
         
        if (search.value === 'no') {
            search_detail.hidden = false;
            searmother.hidden = true;

        }else if (search.value === 'yes') {
            search_detail.hidden = true;
            searmother.hidden = false;

        }else {
            search_detail.hidden = true;
            searmother.hidden = true;
        }
    }

    function toggleSearchguardian() {
        const search = document.getElementById('searchguardian');
        const search_detail = document.getElementById('detailguardian');
        const searguardian = document.getElementById('search_guardian');
         
        if (search.value === 'no') {
            search_detail.hidden = false;
            searguardian.hidden = true;

        }else if (search.value === 'yes') {
            search_detail.hidden = true;
            searguardian.hidden = false;

        }else {
            search_detail.hidden = true;
            searguardian.hidden = true;
        }
    }

    function step4MarkSavedFields(container) {
        if (!container) return;
        var controls = container.querySelectorAll('input.form-control, select.form-control, textarea.form-control');
        controls.forEach(function(el) {
            var hasValue = el.type === 'checkbox' || el.type === 'radio' ? el.checked : (el.value && el.value.trim() !== '');
            if (hasValue) el.classList.add('form-control-saved');
        });
    }

    // Initialiser l'├®tat au chargement
    document.addEventListener('DOMContentLoaded', function() {
        toggleSearchfather();
        toggleSearchmother();
        toggleSearchguardian();

        // Search Parents AJAX - init for Father, Mother, Guardian
        var searchParentsTimeout = {};
        var searchParentsBaseUrl = '{{ url("search-parents") }}';
        ['father', 'mother', 'guardian'].forEach(function(person) {
            var input = document.getElementById('name_' + person + '_input');
            var resultsEl = document.getElementById('parent_results_' + person);
            if (!input || !resultsEl) return;
            input.addEventListener('input', function() {
                var q = (input.value || '').trim();
                clearTimeout(searchParentsTimeout[person]);
                if (q.length < 2) {
                    resultsEl.style.display = 'none';
                    resultsEl.innerHTML = '';
                    return;
                }
                searchParentsTimeout[person] = setTimeout(function() {
                    fetch(searchParentsBaseUrl + '?q=' + encodeURIComponent(q) + '&person=' + (person.charAt(0).toUpperCase() + person.slice(1)))
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            var results = data.results || [];
                            if (results.length === 0) {
                                resultsEl.innerHTML = '<div class="list-group-item text-muted">No parent found</div>';
                            } else {
                                resultsEl.innerHTML = results.map(function(p) {
                                    return '<a href="#" class="list-group-item list-group-item-action" data-id="' + p.id + '" data-name="' + (p.full_name || '').replace(/"/g, '&quot;') + '">' +
                                        '<strong>' + (p.full_name || '') + '</strong><br><small class="text-muted">' + (p.email || '') + (p.phone ? ' | ' + p.phone : '') + '</small></a>';
                                }).join('');
                                resultsEl.querySelectorAll('.list-group-item-action').forEach(function(el) {
                                    el.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        selectParent(person, el.dataset.id, el.dataset.name || el.textContent.trim());
                                        resultsEl.style.display = 'none';
                                        resultsEl.innerHTML = '';
                                    });
                                });
                            }
                            resultsEl.style.display = 'block';
                        })
                        .catch(function() {
                            resultsEl.innerHTML = '<div class="list-group-item text-danger">Search error</div>';
                            resultsEl.style.display = 'block';
                        });
                }, 300);
            });
            input.addEventListener('blur', function() {
                setTimeout(function() { resultsEl.style.display = 'none'; }, 200);
            });
        });
        document.addEventListener('click', function(e) {
            ['father', 'mother', 'guardian'].forEach(function(person) {
                var resultsEl = document.getElementById('parent_results_' + person);
                var input = document.getElementById('name_' + person + '_input');
                if (resultsEl && input && !resultsEl.contains(e.target) && e.target !== input) {
                    resultsEl.style.display = 'none';
                }
            });
        });

        function selectParent(person, id, fullName) {
            var idEl = document.getElementById('parent' + person + '_id');
            var nameEl = document.getElementById('parent_selected_name_' + person);
            var selectedDiv = document.getElementById('parent_selected_' + person);
            var input = document.getElementById('name_' + person + '_input');
            var btn = document.getElementById('btn_save_' + person);
            if (idEl) idEl.value = id;
            if (nameEl) nameEl.textContent = fullName || '';
            if (selectedDiv) selectedDiv.style.display = 'block';
            if (input) input.value = fullName || '';
            if (btn) btn.disabled = false;
        }
        function clearParentSelection(person) {
            var idEl = document.getElementById('parent' + person + '_id');
            var nameEl = document.getElementById('parent_selected_name_' + person);
            var selectedDiv = document.getElementById('parent_selected_' + person);
            var input = document.getElementById('name_' + person + '_input');
            var btn = document.getElementById('btn_save_' + person);
            if (idEl) idEl.value = '';
            if (nameEl) nameEl.textContent = '';
            if (selectedDiv) selectedDiv.style.display = 'none';
            if (input) input.value = '';
            if (btn) btn.disabled = true;
        }
        window.clearParentSelection = clearParentSelection;

        // ├Ç chaque ouverture d'un modal step4, marquer en bleu les champs qui ont une valeur
        ['add_father', 'add_mother', 'add_guardian'].forEach(function(modalId) {
            var modalEl = document.getElementById(modalId);
            if (!modalEl) return;
            modalEl.addEventListener('show.bs.modal', function() {
                var part = modalId.replace('add_', '');
                step4MarkSavedFields(document.getElementById('detail' + part));
            });
        });

        // AJAX submit for step4 forms (add parent + link parent)
        const step4Forms = [
            'form-step5-add-father', 'form-step5-add-mother', 'form-step5-add-guardian',
            'form-step5-link-father', 'form-step5-link-mother', 'form-step5-link-guardian'
        ];
        const errEl = document.getElementById('step5-validation-errors');
        step4Forms.forEach(function(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (errEl) { errEl.classList.add('d-none'); errEl.textContent = ''; }
                const url = form.getAttribute('action');
                const fd = new FormData(form);
                fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                    .then(function(r) { return r.json().then(function(d) { return { ok: r.ok, data: d }; }); })
                    .then(function(result) {
                        if (result.ok && result.data.success) {
                            // Redirection vers la page admission avec le step (Family = step4) — ne pas rouvrir le modal
                            var redirectUrl = result.data.redirect;
                            if (redirectUrl) {
                                window.location.href = redirectUrl;
                                return;
                            }
                            var codeStudent = form.querySelector('[name="code_student"]');
                            codeStudent = codeStudent ? codeStudent.value : '';
                            if (codeStudent) {
                                var baseUrl = "{{ url('admission') }}";
                                var sep = baseUrl.indexOf('?') >= 0 ? '&' : '?';
                                window.location.href = baseUrl + sep + 'code_student=' + encodeURIComponent(codeStudent) + '&step=step4';
                                return;
                            }
                            if (errEl && result.data.message) {
                                errEl.classList.remove('d-none');
                                errEl.classList.remove('alert-danger');
                                errEl.classList.add('alert-success');
                                errEl.textContent = result.data.message;
                            }
                            setTimeout(function() { location.reload(); }, 1500);
                            return;
                        }
                        if (errEl) {
                            errEl.textContent = result.data.message_detail || result.data.message || 'An error occurred.';
                            errEl.classList.remove('d-none');
                        }
                    })
                    .catch(function() {
                        if (errEl) {
                            errEl.textContent = 'Network or server error. Please try again.';
                            errEl.classList.remove('d-none');
                        }
                    });
            });
        });
    });

</script>
