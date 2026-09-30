<style>
    /* Données enregistrées : couleur distincte */
    .form-control.form-control-saved,
    .form-control.form-control-saved:focus {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.06);
    }
    select.form-control.form-control-saved option {
        color: #212529;
    }
    select.form-control.form-control-saved {
        color: #0d6efd;
    }
</style>
<div class="card">

    <div class="card-header bg-light">
        <div class="d-flex align-items-center">
            <span class="bg-white avatar avatar-sm me-2 text-gray-7 flex-shrink-0">
                <i class="ti ti-info-square-rounded fs-16"></i>
            </span>
            <h4 class="text-dark">Step 1</h4>
        </div>
    </div>
    <div class="card-body pb-1 col-md-6">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Academic Year</label>
                <input type="text" class="form-control" placeholder="Enter Label" value="{{$acayear->year}}" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Classroom <span style="color: red;">*</span></label>
                <select name="classroom" id="classroom" class="form-control @if(!empty($step1info->class_current)) form-control-saved @endif" onchange="toggleChange()" required>
                    <option value="">Select Class</option>
                    @foreach($classes as $classe)
                    <option value="{{ $classe->name_classe }}" @if(isset($step1info->class_current) && $step1info->class_current == $classe->name_classe) selected @endif>{{ $classe->name_classe }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div><br>
    <div class="card-body pb-1" id="more_info" @if(empty($step1info->class_current)) hidden @endif>
        {{-- Title: Personal information --}}
        <h5 class="text-dark mb-3 d-flex align-items-center">
            <i class="ti ti-user me-2 text-primary"></i>
            Personal information
        </h5>
        <div class="row row-cols-xxl-3 row-cols-md-6">
            <div class="col-xxl col-xl-3 col-md-6"> 
                <div class="mb-3">
                    <label class="form-label">Gender <span style="color: red;">*</span></label>
                    <select name="gender" class="form-control @if(!empty($step1info->sexe)) form-control-saved @endif" required>
                        <option value="">Select gender</option>
                        <option value="male" @if(isset($step1info->sexe) && strtolower($step1info->sexe) === 'male') selected @endif>Male</option>
                        <option value="female" @if(isset($step1info->sexe) && strtolower($step1info->sexe) === 'female') selected @endif>Female</option>
                    </select>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Last Name <span style="color: red;">*</span></label>
                    <input type="text" name="last_name" value="{{ $step1info->nom ?? '' }}" class="form-control @if(!empty($step1info->nom)) form-control-saved @endif" required>
                    <input type="hidden" name="id_us" id="iduser" value="{{ session('user.id') }}" class="form-control">
                    <input type="text" class="form-control code_student" name="code_student" hidden>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">First Name <span style="color: red;">*</span></label>
                    <input type="text" name="first_name" value="{{ $step1info->prenom ?? '' }}" class="form-control @if(!empty($step1info->prenom)) form-control-saved @endif" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Birthday <span style="color: red;">*</span></label>
                    <div class="input-icon position-relative">
                        @php
                            $birthdayValue = '';
                            if (!empty($step1info->birthday)) {
                                try {
                                    $birthdayValue = \Carbon\Carbon::parse($step1info->birthday)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    $birthdayValue = is_string($step1info->birthday) ? $step1info->birthday : '';
                                }
                            }
                        @endphp
                        <input type="date" name="birthday" value="{{ $birthdayValue }}" class="form-control @if(!empty($step1info->birthday)) form-control-saved @endif" required>
                    </div>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Birth City <span style="color: red;">*</span></label>
                    <input type="text" name="birth_city" value="{{ $step1info->birth_city ?? '' }}" class="form-control @if(!empty($step1info->birth_city)) form-control-saved @endif" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Birth Country <span style="color: red;">*</span></label>
                    <select name="birth_country" class="form-control @if(!empty($step1info->birth_country)) form-control-saved @endif" required>
                        <option value="">Select birth country</option>
                        @foreach($countries ?? [] as $country)
                        <option value="{{ $country->nom_en_gb }}" @if(isset($step1info->birth_country) && ($step1info->birth_country == $country->nom_en_gb || $step1info->birth_country == $country->nom_fr_fr)) selected @endif>{{ $country->nom_fr_fr ?? $country->nom_en_gb }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nationality <span style="color: red;">*</span></label>
                    <select name="nationality" class="form-control @if(!empty($step1info->nationality)) form-control-saved @endif" required>
                        <option value="">Select nationality</option>
                        @foreach($national as $nationals)
                        <option value="{{ $nationals->name_nationality }}" @if(isset($step1info->nationality) && $step1info->nationality == $nationals->name_nationality) selected @endif>{{ $nationals->name_nationality }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">First Language <span style="color: red;">*</span></label>
                    <input type="text" name="first_lang" value="{{ $step1info->first_lang ?? '' }}" class="form-control @if(!empty($step1info->first_lang)) form-control-saved @endif" required>
                </div>
            </div>
            <div class="col-xxl col-xl-3 col-md-6">
                <div class="mb-3">
                    <label class="form-label">ID photo @if(empty($step1info->file))<span style="color: red;">*</span>@endif</label>
                    <input type="file" name="photo" class="form-control" accept=".pdf,.png,.jpg,.jpeg" @if(empty($step1info->file)) required @endif>
                    <p class="small text-muted mt-1 mb-0">Types : pdf, png, jpg, jpeg — Taille max : 5 Mo</p>
                    @if(!empty($step1info->file))
                    <div class="mt-1 small text-muted">
                        <i class="ti ti-file me-1"></i>
                        <a href="{{ asset(str_starts_with($step1info->file ?? '', 'uploads/') ? $step1info->file : 'storage/' . $step1info->file) }}" target="_blank" rel="noopener">{{ basename($step1info->file) }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <hr class="my-4 border-secondary opacity-25">

        {{-- Title: File upload (required files depend on selected class / level from reqFiles) --}}
        <h5 class="text-dark mb-3 d-flex align-items-center">
            <i class="ti ti-file-upload me-2 text-primary"></i>
            File upload <span class="text-muted small ms-2">(required documents depend on class level)</span>
        </h5>
        <div id="required-files-container" class="row row-cols-xxl-3 row-cols-md-6">
            {{-- Filled by JS from requiredFilesByClass when classroom is selected --}}
            <div id="required-files-placeholder" class="col-12 text-muted">
                Select a class above to see required documents to upload.
            </div>
        </div>
        <p class="text-muted small mb-0 mt-2 text-end">Document format : <span class="text-danger">pdf, png, jpg, jpeg</span> (max 5MB)</p>
    </div>
</div>

@php
    $step1FileUrls = [
        'filepassport' => null,
        'fileacademi' => null,
        'fileexams' => null,
        'filevacc' => null,
    ];
    $step1FileNames = [
        'filepassport' => null,
        'fileacademi' => null,
        'fileexams' => null,
        'filevacc' => null,
    ];
    if (isset($step1info->filepassport) && $step1info->filepassport) {
        $step1FileUrls['filepassport'] = str_starts_with($step1info->filepassport, 'uploads/') ? asset($step1info->filepassport) : asset('storage/' . $step1info->filepassport);
        $step1FileNames['filepassport'] = basename($step1info->filepassport);
    }
    if (isset($step1info->fileacademi) && $step1info->fileacademi) {
        $step1FileUrls['fileacademi'] = str_starts_with($step1info->fileacademi, 'uploads/') ? asset($step1info->fileacademi) : asset('storage/' . $step1info->fileacademi);
        $step1FileNames['fileacademi'] = basename($step1info->fileacademi);
    }
    if (isset($step1info->fileexams) && $step1info->fileexams) {
        $step1FileUrls['fileexams'] = str_starts_with($step1info->fileexams, 'uploads/') ? asset($step1info->fileexams) : asset('storage/' . $step1info->fileexams);
        $step1FileNames['fileexams'] = basename($step1info->fileexams);
    }
    if (isset($step1info->filevacc) && $step1info->filevacc) {
        $step1FileUrls['filevacc'] = str_starts_with($step1info->filevacc, 'uploads/') ? asset($step1info->filevacc) : asset('storage/' . $step1info->filevacc);
        $step1FileNames['filevacc'] = basename($step1info->filevacc);
    }
@endphp
<script>
    const requiredFilesByClass = @json($requiredFilesByClass ?? []);
    const step1ExistingFiles = @json($step1FileUrls);
    const step1ExistingFilenames = @json($step1FileNames);

    function buildFileInput(item, existingUrl, existingFilename) {
        const hasExisting = existingUrl && existingUrl.length > 0;
        return `
            <div class="col-xxl col-xl-4 col-md-6">
                <div class="mb-3">
                    <label class="form-label">${item.description} ${!hasExisting ? '<span style="color: red;">*</span>' : ''}</label>
                    <input type="file" name="${item.field_name}" class="form-control" accept=".pdf,.png,.jpg,.jpeg" ${!hasExisting ? 'required' : ''}>
                    <p class="small text-muted mt-1 mb-0">Types : pdf, png, jpg, jpeg — Taille max : 5 Mo</p>
                    ${hasExisting ? `<div class="mt-1 small text-muted"><i class="ti ti-file me-1"></i><a href="${existingUrl}" target="_blank" rel="noopener">${existingFilename || 'View file'}</a></div>` : ''}
                </div>
            </div>
        `;
    }

    function renderRequiredFilesForClass(className) {
        const container = document.getElementById('required-files-container');
        const placeholder = document.getElementById('required-files-placeholder');
        const selectedClass = (className || '').trim();
        const matchedClassKey = Object.keys(requiredFilesByClass).find(key => key.trim().toLowerCase() === selectedClass.toLowerCase());
        const list = matchedClassKey ? requiredFilesByClass[matchedClassKey] : [];
        if (!list || list.length === 0) {
            if (placeholder) placeholder.hidden = false;
            if (placeholder) {
                placeholder.textContent = selectedClass
                    ? 'No required documents configured for this class level.'
                    : 'Select a class above to see required documents to upload.';
            }
            container.querySelectorAll('.req-file-block').forEach(el => el.remove());
            return;
        }
        if (placeholder) placeholder.hidden = true;
        container.querySelectorAll('.req-file-block').forEach(el => el.remove());
        list.forEach(item => {
            const existingUrl = step1ExistingFiles[item.files_column] || null;
            const existingFilename = step1ExistingFilenames[item.files_column] || null;
            const wrap = document.createElement('div');
            wrap.innerHTML = buildFileInput(item, existingUrl, existingFilename).trim();
            const block = wrap.firstElementChild;
            if (block) block.classList.add('req-file-block');
            container.appendChild(block || wrap);
        });
    }

    function toggleChange() {
        const classroom = document.getElementById('classroom');
        const other_info = document.getElementById('more_info');

        if (classroom.value && classroom.value !== 'Select Class') {
            other_info.hidden = false;
            renderRequiredFilesForClass(classroom.value);
        } else {
            other_info.hidden = true;
            const placeholder = document.getElementById('required-files-placeholder');
            if (placeholder) {
                placeholder.hidden = false;
                placeholder.textContent = 'Select a class above to see required documents to upload.';
            }
            document.querySelectorAll('#required-files-container .req-file-block').forEach(el => el.remove());
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleChange();
    });
</script>
