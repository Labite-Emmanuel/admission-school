@php
    $step2info = $step2info ?? (object) [];
    $step3info = $step3info ?? (object) [];
@endphp
<style>
    /* Données enregistrées : couleur distincte (comme step1) */
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
    .info-block {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }
    .info-block-title {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }
    .medical-module-header {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
    }
    .medical-module-header h5 {
        margin: 0;
        font-weight: 600;
        color: #155724;
    }
    .form-check-input.form-control-saved { accent-color: #0d6efd; }
    .learning-behavioural-header {
        background: #e8f4fd;
        border: 1px solid #bee5eb;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
    }
    .learning-behavioural-header h5 { margin: 0; font-weight: 600; color: #0c5460; }
    .disclaimer-text { font-size: 0.85rem; color: #6c757d; margin-bottom: 0.5rem; }
</style>
<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex align-items-center">
            <span class="bg-white avatar avatar-sm me-2 text-gray-7 flex-shrink-0">
                <i class="ti ti-info-square-rounded fs-16"></i>
            </span>
            <h4 class="text-dark">Step 2 - Medical Information / Learning &amp; Behavioural</h4>
        </div>
    </div>
    <div class="card-body pb-1">
        <input type="hidden" name="code_student" class="code_student">
        <input type="hidden" name="id_us" value="{{ session('user.id') }}">

        <!-- Bloc 1 : Informations de base (Blood group, Doctor, Recommendation) -->
        <div class="info-block">
            <div class="info-block-title">Basic Information</div>
            <div class="row row-cols-xxl-3 row-cols-md-6">
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Blood Group <span style="color: red;">*</span></label>
                        <select name="blood_group" class="form-control @if(!empty($step2info->blood_group)) form-control-saved @endif" required>
                            <option value="">Select blood group</option>
                            <option value="A+" @if(isset($step2info->blood_group) && $step2info->blood_group == 'A+') selected @endif>A+</option>
                            <option value="B+" @if(isset($step2info->blood_group) && $step2info->blood_group == 'B+') selected @endif>B+</option>
                            <option value="AB+" @if(isset($step2info->blood_group) && $step2info->blood_group == 'AB+') selected @endif>AB+</option>
                            <option value="O+" @if(isset($step2info->blood_group) && $step2info->blood_group == 'O+') selected @endif>O+</option>
                            <option value="O-" @if(isset($step2info->blood_group) && $step2info->blood_group == 'O-') selected @endif>O-</option>
                            <option value="AB-" @if(isset($step2info->blood_group) && $step2info->blood_group == 'AB-') selected @endif>AB-</option>
                            <option value="B-" @if(isset($step2info->blood_group) && $step2info->blood_group == 'B-') selected @endif>B-</option>
                            <option value="A-" @if(isset($step2info->blood_group) && $step2info->blood_group == 'A-') selected @endif>A-</option>
                            <option value="N/A" @if(isset($step2info->blood_group) && $step2info->blood_group == 'N/A') selected @endif>N/A</option>
                        </select>
                    </div>
                </div>
                <div class="col-xxl col-xl-3 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Doctor Name </label>
                        <input type="text" class="form-control" value="{{ $step2info->doctor_name ?? '' }}" name="doctor_name">
                    </div>
                </div>
                    <div class="col-xxl col-xl-3 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Doctor Contact</label>
                            <input type="tel" class="form-control" value="{{ $step2info->doctor_contact ?? '' }}" name="doctor_contact" placeholder="+22500000000">
                        </div>
                    </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Does your child have any medical recommendation? <span style="color: red;">*</span></label>
                        <div class="d-flex gap-3">
                            <label class="form-check">
                                <input type="radio" name="any_recom" class="form-check-input" value="no" @if(!isset($step2info->any_recommandation) || strtolower($step2info->any_recommandation) === 'no') checked @endif onchange="toggleMedicalModule()">
                                <span class="form-check-label">No</span>
                            </label>
                            <label class="form-check">
                                <input type="radio" name="any_recom" class="form-check-input" value="yes" @if(isset($step2info->any_recommandation) && strtolower($step2info->any_recommandation) === 'yes') checked @endif onchange="toggleMedicalModule()">
                                <span class="form-check-label">Yes</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module MEDICAL INFORMATION* (visible si any_recom = yes) -->
        <div id="medicalInformationModule" class="mb-4" style="display: {{ (isset($step2info->any_recommandation) && strtolower($step2info->any_recommandation) === 'yes') ? 'block' : 'none' }};">
            <div class="medical-module-header">
                <h5>MEDICAL INFORMATION*</h5>
            </div>

            <!-- Bloc MEDICATION -->
            <div class="info-block">
                <div class="info-block-title">MEDICATION</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Does your child take medicine for specific health conditions?</label>
                            <div class="d-flex gap-3">
                                <label class="form-check">
                                    <input type="radio" name="any_medecine" class="form-check-input" value="yes" @if(isset($step2info->medecine_health) && strtolower($step2info->medecine_health) === 'yes') checked @endif onchange="toggleMedicationFields()">
                                    <span class="form-check-label">Yes (if Yes specify below)</span>
                                </label>
                                <label class="form-check">
                                    <input type="radio" name="any_medecine" class="form-check-input" value="no" @if(!isset($step2info->medecine_health) || strtolower($step2info->medecine_health) === 'no') checked @endif onchange="toggleMedicationFields()">
                                    <span class="form-check-label">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="medicationDetails" style="display: none;">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="mb-3">
                                <label class="form-label">List medication(s):</label>
                                <input type="text" name="medication_list" class="form-control @if(!empty($step2info->medication_list)) form-control-saved @endif" value="{{ $step2info->medication_list ?? '' }}" placeholder="e.g. Medication 1, Medication 2">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check">
                            <input type="hidden" name="any_medic" id="any_medic_input" value="{{ (isset($step2info->medication_school) && strtolower($step2info->medication_school) === 'yes') ? 'yes' : 'no' }}">
                            <input type="checkbox" id="medication_at_school" class="form-check-input" @if(isset($step2info->medication_school) && strtolower($step2info->medication_school) === 'yes') checked @endif onchange="document.getElementById('any_medic_input').value = this.checked ? 'yes' : 'no'">
                            <span class="form-check-label">Medication must be given and/or available at school</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Bloc ALLERGY -->
            <div class="info-block">
                <div class="info-block-title">ALLERGY</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Has your child any Allergy?</label>
                            <div class="d-flex gap-3">
                                <label class="form-check">
                                    <input type="radio" name="any_allergy" class="form-check-input" value="yes" @if(isset($step2info->allergy) && strtolower($step2info->allergy) === 'yes') checked @endif onchange="toggleAllergyFields()">
                                    <span class="form-check-label">Yes (if Yes specify below)</span>
                                </label>
                                <label class="form-check">
                                    <input type="radio" name="any_allergy" class="form-check-input" value="no" @if(!isset($step2info->allergy) || strtolower($step2info->allergy) === 'no') checked @endif onchange="toggleAllergyFields()">
                                    <span class="form-check-label">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="allergyDetails" style="display: none;">
                    <div class="row row-cols-1 row-cols-md-2">
                        <div class="col mb-3">
                            <label class="form-label">Food</label>
                            <input type="text" name="allergy_food" class="form-control @if(!empty($step2info->allergy_food)) form-control-saved @endif" value="{{ $step2info->allergy_food ?? '' }}" placeholder="Specify if applicable">
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Insect</label>
                            <input type="text" name="allergy_insect" class="form-control @if(!empty($step2info->allergy_insect)) form-control-saved @endif" value="{{ $step2info->allergy_insect ?? '' }}" placeholder="Specify if applicable">
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Medicine</label>
                            <input type="text" name="allergy_medicine" class="form-control @if(!empty($step2info->allergy_medicine)) form-control-saved @endif" value="{{ $step2info->allergy_medicine ?? '' }}" placeholder="Specify if applicable">
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Other</label>
                            <input type="text" name="allergy_other" class="form-control @if(!empty($step2info->allergy_other)) form-control-saved @endif" value="{{ $step2info->allergy_other ?? '' }}" placeholder="Specify if applicable">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Type of allergic reaction</label>
                            <input type="text" name="allergy_reaction" class="form-control @if(!empty($step2info->allergy_reaction)) form-control-saved @endif" value="{{ $step2info->allergy_reaction ?? '' }}">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Response required</label>
                            <input type="text" name="allergy_resp_required" class="form-control @if(!empty($step2info->allergy_resp_required)) form-control-saved @endif" value="{{ $step2info->allergy_resp_required ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bloc OTHER INFORMATION -->
            <div class="info-block">
                <div class="info-block-title">OTHER INFORMATION</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Does your child have any medical conditions?</label>
                            <div class="d-flex gap-3">
                                <label class="form-check">
                                    <input type="radio" name="has_other_conditions" class="form-check-input" value="yes" @if(isset($step2info->has_other_conditions) && strtolower($step2info->has_other_conditions) === 'yes') checked @endif onchange="toggleOtherConditionsField()">
                                    <span class="form-check-label">Yes (if Yes specify below or enclose confidential separate sheet)</span>
                                </label>
                                <label class="form-check">
                                    <input type="radio" name="has_other_conditions" class="form-check-input" value="no" @if(!isset($step2info->has_other_conditions) || strtolower($step2info->has_other_conditions) === 'no') checked @endif onchange="toggleOtherConditionsField()">
                                    <span class="form-check-label">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="otherConditionsDetails" style="display: none;">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Specify medical conditions or attach confidential sheet</label>
                                <textarea name="other_medical_info" class="form-control @if(!empty($step2info->other_medication_infos)) form-control-saved @endif" rows="3">{{ $step2info->other_medication_infos ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section fichiers conditionnelle (Justifications) -->
            <div class="info-block">
                <div class="info-block-title">Justifications & Additional Information</div>
                <div class="row row-cols-xxl-2 row-cols-md-6">
                    <div class="col-xxl col-xl-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Medical justification</label>
                            <input type="file" class="form-control" name="medical_justification" accept=".pdf,.png,.jpg,.jpeg">
                            <p class="small text-muted mt-1 mb-0">Types : pdf, png, jpg, jpeg — Taille max : 5 Mo</p>
                            @if(!empty($step2info->other_med_info_file))
                            <div class="mt-1 small text-muted">
                                <i class="ti ti-file me-1"></i>
                                <a href="{{ asset(str_starts_with($step2info->other_med_info_file ?? '', 'uploads/') ? $step2info->other_med_info_file : 'storage/' . $step2info->other_med_info_file) }}" target="_blank" rel="noopener">{{ basename($step2info->other_med_info_file) }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- <div class="col-xxl col-xl-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Learning difficulties</label>
                            <textarea class="form-control @if(!empty($step2info->learning_difficulty)) form-control-saved @endif" name="learning_difficulties" rows="2">{{ $step2info->learning_difficulty ?? '' }}</textarea>
                        </div>
                    </div> -->
                    <div class="col-xxl col-xl-6 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Learning difficulties justification</label>
                            <input type="file" class="form-control" name="learning_justification" accept=".pdf,.png,.jpg,.jpeg">
                            <p class="small text-muted mt-1 mb-0">Types : pdf, png, jpg, jpeg — Taille max : 5 Mo</p>
                            @if(!empty($step2info->learning_diff_file))
                            <div class="mt-1 small text-muted">
                                <i class="ti ti-file me-1"></i>
                                <a href="{{ asset(str_starts_with($step2info->learning_diff_file ?? '', 'uploads/') ? $step2info->learning_diff_file : 'storage/' . $step2info->learning_diff_file) }}" target="_blank" rel="noopener">{{ basename($step2info->learning_diff_file) }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning & Behavioural Information (merged from former Step 3) -->
        <div class="learning-behavioural-header mt-4">
            <h5>LEARNING &amp; BEHAVIOURAL INFORMATION *</h5>
            <p class="disclaimer-text mb-1">All information provided will be treated as strictly confidential and used solely to determine whether the school can adequately and safely meet the needs of the child within its educational framework.</p>
            <p class="disclaimer-text mb-0">Failure to disclose accurate and complete information may affect the school's ability to adequately support your child and may result in a review of the enrolment decision.</p>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Does your child have any learning or behavioural condition that may require special support or that could affect his/her school life or the learning environment of others?</label>
            <div class="d-flex gap-4">
                <label class="form-check">
                    <input type="radio" name="has_condition" class="form-check-input" value="yes" @if(isset($step3info->has_condition) && strtolower($step3info->has_condition) === 'yes') checked @endif onchange="toggleConditionDetails()">
                    <span class="form-check-label">Yes (if Yes specify below)</span>
                </label>
                <label class="form-check">
                    <input type="radio" name="has_condition" class="form-check-input" value="no" @if(!isset($step3info->has_condition) || strtolower($step3info->has_condition) === 'no') checked @endif onchange="toggleConditionDetails()">
                    <span class="form-check-label">No</span>
                </label>
            </div>
        </div>

        <div id="conditionDetails" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <div class="border rounded p-3 mb-3" style="background: #f8f9fa;">
                        <div class="info-block-title">LEARNING DIFFICULTIES</div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="learning_dyslexia" id="learning_dyslexia" class="form-check-input" value="1" @if($step3info->learning_dyslexia ?? false) checked @endif>
                            <label class="form-check-label" for="learning_dyslexia">Dyslexia</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="learning_dyscalculia" id="learning_dyscalculia" class="form-check-input" value="1" @if($step3info->learning_dyscalculia ?? false) checked @endif>
                            <label class="form-check-label" for="learning_dyscalculia">Dyscalculia</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="learning_add_adhd" id="learning_add_adhd" class="form-check-input" value="1" @if($step3info->learning_add_adhd ?? false) checked @endif>
                            <label class="form-check-label" for="learning_add_adhd">Attention Deficit Disorder (ADD / ADHD)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="learning_autism_spectrum" id="learning_autism_spectrum" class="form-check-input" value="1" @if($step3info->learning_autism_spectrum ?? false) checked @endif>
                            <label class="form-check-label" for="learning_autism_spectrum">Autism Spectrum Disorder (ASD)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="learning_speech_language" id="learning_speech_language" class="form-check-input" value="1" @if($step3info->learning_speech_language ?? false) checked @endif>
                            <label class="form-check-label" for="learning_speech_language">Speech and language difficulties</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="learning_global_delay" id="learning_global_delay" class="form-check-input" value="1" @if($step3info->learning_global_delay ?? false) checked @endif>
                            <label class="form-check-label" for="learning_global_delay">Global learning delay</label>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Other learning difficulty (please specify):</label>
                            <input type="text" name="learning_other_specify" class="form-control form-control-sm @if(!empty($step3info->learning_other_specify)) form-control-saved @endif" value="{{ $step3info->learning_other_specify ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 mb-3" style="background: #f8f9fa;">
                        <div class="info-block-title">BEHAVIOURAL, EMOTIONAL &amp; DEVELOPMENTAL DIFFICULTIES</div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="behaviour_group_setting" id="behaviour_group_setting" class="form-check-input" value="1" @if($step3info->behaviour_group_setting ?? false) checked @endif>
                            <label class="form-check-label" for="behaviour_group_setting">Difficulty managing behaviour in a group setting</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="behaviour_aggressive" id="behaviour_aggressive" class="form-check-input" value="1" @if($step3info->behaviour_aggressive ?? false) checked @endif>
                            <label class="form-check-label" for="behaviour_aggressive">Aggressive or violent behaviour</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="behaviour_impulsivity" id="behaviour_impulsivity" class="form-check-input" value="1" @if($step3info->behaviour_impulsivity ?? false) checked @endif>
                            <label class="form-check-label" for="behaviour_impulsivity">Impulsivity or lack of self-control</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="behaviour_emotional_social" id="behaviour_emotional_social" class="form-check-input" value="1" @if($step3info->behaviour_emotional_social ?? false) checked @endif>
                            <label class="form-check-label" for="behaviour_emotional_social">Emotional or social difficulties</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="behaviour_sensory" id="behaviour_sensory" class="form-check-input" value="1" @if($step3info->behaviour_sensory ?? false) checked @endif>
                            <label class="form-check-label" for="behaviour_sensory">Sensory difficulties (hearing, vision, etc.)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="behaviour_toileting" id="behaviour_toileting" class="form-check-input" value="1" @if($step3info->behaviour_toileting ?? false) checked @endif>
                            <label class="form-check-label" for="behaviour_toileting">Toileting or hygiene-related difficulties</label>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Other concerns (please specify):</label>
                            <input type="text" name="behaviour_other_specify" class="form-control form-control-sm @if(!empty($step3info->behaviour_other_specify)) form-control-saved @endif" value="{{ $step3info->behaviour_other_specify ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">OTHER INFORMATION</label>
                <p class="small text-muted">Please provide any further information that may help the school assess its ability to meet your child's needs safely and appropriately.</p>
                <textarea name="other_information" class="form-control @if(!empty($step3info->other_information)) form-control-saved @endif" rows="4">{{ $step3info->other_information ?? '' }}</textarea>
            </div>
        </div>

        <span style="text-muted small mb-0 mt-2 text-end">Document format : <span style="color: red;"> pdf, png, jpg, jpeg</span></span><br><br><br><br>
    </div>
</div>

<script>
    function toggleMedicalModule() {
        const checked = document.querySelector('input[name="any_recom"]:checked');
        const value = checked ? checked.value : 'no';
        const medicalModule = document.getElementById('medicalInformationModule');
        medicalModule.style.display = value === 'yes' ? 'block' : 'none';
        if (value === 'yes') {
            toggleMedicationFields();
            toggleAllergyFields();
            toggleOtherConditionsField();
        }
    }

    function toggleMedicationFields() {
        const takesMedicine = document.querySelector('input[name="any_medecine"]:checked');
        const medicationDetails = document.getElementById('medicationDetails');
        medicationDetails.style.display = (takesMedicine && takesMedicine.value === 'yes') ? 'block' : 'none';
    }

    function toggleAllergyFields() {
        const hasAllergy = document.querySelector('input[name="any_allergy"]:checked');
        const allergyDetails = document.getElementById('allergyDetails');
        allergyDetails.style.display = (hasAllergy && hasAllergy.value === 'yes') ? 'block' : 'none';
    }

    function toggleOtherConditionsField() {
        const hasOther = document.querySelector('input[name="has_other_conditions"]:checked');
        const otherDetails = document.getElementById('otherConditionsDetails');
        otherDetails.style.display = (hasOther && hasOther.value === 'yes') ? 'block' : 'none';
    }

    function toggleConditionDetails() {
        var hasCondition = document.querySelector('input[name="has_condition"]:checked');
        var details = document.getElementById('conditionDetails');
        details.style.display = (hasCondition && hasCondition.value === 'yes') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleMedicalModule();
        toggleConditionDetails();
    });
</script>
