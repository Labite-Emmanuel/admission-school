<?php $page = 'admission'; ?>
@extends('layout.mainlayout')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header admission-page-header">
            <div class="row align-items-center">
                <div class="col">
                    <form id="admissionForm" action="{{ url('home-admission') }}" method="post" class="d-inline">
                        @csrf
                        <input type="hidden" name="user_id" id="id_user" value="{{ session('user.id') }}">
                    </form>
                    <a href="#" onclick="document.getElementById('admissionForm').submit();" class="btn btn-outline-primary btn-sm me-2 mb-5">
                        <i class="ti ti-arrow-left me-1"></i> Back to list
                    </a>
                    <h3 class="page-title mb-1 mt-2"><i class="ti ti-file-certificate me-2"></i>Admission application</h3>
                    <p class="mb-0 text-muted">Complete the steps below to submit your application.</p>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-12">
                <!-- Wizard card -->
                <div class="card admission-wizard-card">
                    <!-- Step progress -->
                    <div class="card-header border-0 bg-light py-4 px-3">
                        <div class="admission-stepper">
                            <div class="steps-track">
                                @foreach(['step1' => 'PERSONAL INFORMATION', 'step2' => 'MEDICAL / LEARNING & BEHAVIOURAL', 'step3' => 'EMERGENCY AND AUTHORISE PERSONNE', 'step4' => 'FAMILY INFORMATION', 'step5' => 'COMMITMENTS AND AGREEMENTS'] as $stepId => $label)
                                <div class="step-item" data-step="{{ $stepId }}">
                                    <button type="button" class="step-trigger" id="{{ $stepId }}-tab" aria-controls="{{ $stepId }}" aria-selected="false" data-bs-toggle="pill" data-bs-target="#{{ $stepId }}" role="tab">
                                        <span class="step-circle"><span class="step-num">{{ $loop->iteration }}</span></span>
                                        <span class="step-label">{{ $label }}</span>
                                    </button>
                                    @if(!$loop->last)
                                    <div class="step-connector" aria-hidden="true"></div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <!-- Step content -->
                        <div class="tab-content admission-tab-content" id="pills-tabContent">
                            <!-- Step 1 -->
                            <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                                <div id="step1-validation-errors" class="alert alert-danger d-none" role="alert">
                                    <strong>Validation failed</strong>
                                    <ul id="step1-validation-list" class="mb-0 mt-2"></ul>
                                </div>
                                <form id="form-step1" action="{{ url('post_info_step1') }}" method="post" enctype="multipart/form-data"
                                    data-has-file-photo="{{ (isset($step1info) && !empty($step1info->file)) ? '1' : '0' }}"
                                    data-has-file-birth_certif="{{ (isset($step1info) && !empty($step1info->filepassport)) ? '1' : '0' }}"
                                    data-has-file-previous_academic="{{ (isset($step1info) && !empty($step1info->fileacademi)) ? '1' : '0' }}"
                                    data-has-file-transfer_certif="{{ (isset($step1info) && !empty($step1info->fileexams)) ? '1' : '0' }}"
                                    data-has-file-updated_vacci="{{ (isset($step1info) && !empty($step1info->filevacc)) ? '1' : '0' }}">
                                    @csrf
                                    @include('admissions.steps.step1')
                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <button type="button" class="btn btn-primary next-step" data-current="step1" data-next="step2">
                                            Next <i class="ti ti-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Step 2 -->
                            <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                                <div id="step2-validation-errors" class="alert alert-danger d-none" role="alert">
                                    <strong>Validation failed</strong>
                                    <ul id="step2-validation-list" class="mb-0 mt-2"></ul>
                                </div>
                                <form id="admissionstep1" action="{{ url('get_step1_data') }}" method="get" class="d-none">
                                    @csrf
                                    <input type="hidden" class="form-control code_student" name="code_student">
                                </form>
                                <form id="form-step2" action="{{ url('post_info_step2') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @include('admissions.steps.step2')
                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <button type="button" class="btn btn-light prev-step" data-prev="step1">
                                            <i class="ti ti-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-step" data-current="step2" data-next="step3">
                                            Next <i class="ti ti-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Step 3 - PICKUP (formerly step4) -->
                            <div class="tab-pane fade @if(session('current_step') === 'step4') show active @endif" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                                <div id="step3-validation-errors" class="alert alert-danger d-none" role="alert">
                                    <strong>Validation failed</strong>
                                    <ul id="step3-validation-list" class="mb-0 mt-2"></ul>
                                </div>
                                <form id="admissionstep3" action="{{ url('get_step3_data') }}" method="get" class="d-none">
                                    @csrf
                                    <input type="hidden" class="form-control code_student" name="code_student">
                                </form>
                                <form id="form-step3" action="{{ url('post_info_step4') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @include('admissions.steps.step4')
                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <button type="button" class="btn btn-light prev-step" data-prev="step2">
                                            <i class="ti ti-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary next-step" data-current="step3" data-next="step4">
                                            Next <i class="ti ti-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Step 4 - FAMILY (formerly step5) -->
                            <div class="tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step4-tab">
                                <form id="admissionstep4" action="{{ url('get_step5_data') }}" method="get" class="d-none">
                                    @csrf
                                    <input type="hidden" class="form-control code_student" name="code_student">
                                </form>
                                @include('admissions.steps.step5')
                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                    <button type="button" class="btn btn-light prev-step" data-prev="step3">
                                        <i class="ti ti-arrow-left me-1"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary next-step" data-current="step4" data-next="step5">
                                        Next <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 5 - COMMITMENTS (formerly step6) -->
                            <div class="tab-pane fade" id="step5" role="tabpanel" aria-labelledby="step5-tab">
                                <div id="step5-validation-errors" class="alert alert-danger d-none" role="alert"></div>
                                <form id="admissionstep5" action="{{ url('get_step6_data') }}" method="get" class="d-none">
                                    @csrf
                                    <input type="hidden" class="form-control code_student" name="code_student">
                                </form>
                                <form id="form-step5" action="{{ url('post_info_step6') }}" method="post">
                                    @csrf
                                    @include('admissions.steps.step6')
                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <button type="button" class="btn btn-light prev-step" data-prev="step4">
                                            <i class="ti ti-arrow-left me-1"></i> Previous
                                        </button>
                                        <button type="submit" class="btn btn-success" data-current="step5">
                                            <i class="ti ti-send me-1"></i> Submit application
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styles limités au wizard admission --}}
<style>
    .admission-page-header .btn-outline-primary { border-radius: 6px; }
    .btn.admission-next-loading { cursor: wait; opacity: 0.85; pointer-events: none; }
    .admission-wizard-card { border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
    .admission-stepper { overflow-x: auto; padding: 8px 0; -webkit-overflow-scrolling: touch; }
    .admission-stepper .steps-track {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        min-width: min(100%, 560px);
        margin: 0 auto;
        position: relative;
    }
    .admission-stepper .step-item {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
    }
    .admission-stepper .step-item:last-child { flex: 0; }
    .admission-stepper .step-trigger {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 4px 2px;
        border: none;
        background: transparent;
        cursor: pointer;
        border-radius: 10px;
        transition: background .15s, transform .15s;
        position: relative;
        z-index: 1;
    }
    .admission-stepper .step-trigger:hover:not(.disabled) {
        background: rgba(61, 94, 225, .08);
    }
    .admission-stepper .step-trigger.disabled {
        cursor: not-allowed;
        pointer-events: none;
        opacity: .55;
    }
    .admission-stepper .step-trigger.completed { cursor: pointer; }
    .admission-stepper .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        transition: background .2s, color .2s, box-shadow .2s;
    }
    .admission-stepper .step-trigger.active .step-circle {
        background: var(--tb-primary, #3d5ee1);
        color: #fff;
        box-shadow: 0 0 0 3px rgba(61, 94, 225, .25);
    }
    .admission-stepper .step-trigger.completed .step-circle {
        background: var(--tb-success, #10b981);
        color: #fff;
    }
    .admission-stepper .step-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-align: center;
        line-height: 1.2;
        max-width: 72px;
    }
    .admission-stepper .step-trigger.active .step-label { color: var(--tb-primary, #3d5ee1); }
    .admission-stepper .step-trigger.completed .step-label { color: #0f766e; }
    .admission-stepper .step-connector {
        flex: 1;
        height: 3px;
        margin: 0 4px;
        margin-bottom: 22px;
        background: #e2e8f0;
        border-radius: 2px;
        transition: background .25s;
    }
    .admission-stepper .step-item.completed .step-connector { background: var(--tb-success, #10b981); }
    .admission-tab-content .tab-pane { padding-top: 12px; }
    @media (max-width: 768px) {
        .admission-stepper .step-circle { width: 36px; height: 36px; font-size: 13px; }
        .admission-stepper .step-label { font-size: 10px; max-width: 56px; }
    }
</style>

<script>
(function waitForJQuery() {
    if (typeof window.jQuery === 'undefined') { setTimeout(waitForJQuery, 50); return; }
    var $ = window.jQuery;
    $(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var isNewAdmission = urlParams.get('new') === '1' || urlParams.get('new') === 'true';
    var getCodeStudent = isNewAdmission ? null : urlParams.get('code_student');
    var getStep = urlParams.get('step');
    var codeStudent = isNewAdmission ? null : localStorage.getItem('code_student');

    if (isNewAdmission) {
        localStorage.removeItem('code_student');
        localStorage.removeItem('current_step');
    }

    /** Normalize step to "step1".."step5" (merged: step2+step3; URL may have old step4/5/6) */
    function normalizeStep(step) {
        if (!step) return 'step1';
        var s = String(step).trim();
        var n = parseInt(s, 10);
        if (!isNaN(n) && n >= 1 && n <= 5) return 'step' + n;
        if (/^step[1-5]$/i.test(s)) return s.toLowerCase();
        if (/^step6$/i.test(s)) return 'step5';
        if (/^step5$/i.test(s)) return 'step4';
        if (/^step4$/i.test(s)) return 'step3';
        if (/^step3$/i.test(s)) return 'step2';
        return 'step1';
    }

    if (getCodeStudent) {
        $('.code_student').val(getCodeStudent);
        localStorage.setItem('code_student', getCodeStudent);
    } else if (codeStudent) {
        $('.code_student').val(codeStudent);
    } else {
        var code = generateCode();
        localStorage.setItem('code_student', code);
        $('.code_student').val(code);
    }

    var stepToApply = (isNewAdmission && getStep) ? normalizeStep(getStep) : (getStep ? normalizeStep(getStep) : (localStorage.getItem('current_step') ? normalizeStep(localStorage.getItem('current_step')) : 'step1'));
    if (isNewAdmission) stepToApply = 'step1';
    localStorage.setItem('current_step', stepToApply);

    function restoreCurrentStep(step) {
        var stepId = normalizeStep(step);
        var steps = ['step1', 'step2', 'step3', 'step4', 'step5'];
        var currentIndex = steps.indexOf(stepId);
        if (currentIndex < 0) currentIndex = 0;

        $('.admission-stepper .step-trigger').removeClass('active completed disabled');
        $('.admission-stepper .step-item').removeClass('completed');
        $('.admission-tab-content .tab-pane').removeClass('show active');

        $('#' + stepId + '-tab').addClass('active').removeClass('disabled').attr('aria-selected', 'true');
        $('#' + stepId).addClass('show active');

        steps.forEach(function(stepName, index) {
            var tab = $('#' + stepName + '-tab');
            var item = tab.closest('.step-item');
            if (index < currentIndex) {
                tab.addClass('completed').removeClass('disabled');
                item.addClass('completed');
            } else if (index > currentIndex) {
                tab.addClass('disabled');
            }
            if (index !== currentIndex) tab.attr('aria-selected', 'false');
        });
    }

    restoreCurrentStep(stepToApply);

    $('.admission-stepper .step-trigger').on('click', function(e) {
        if ($(this).hasClass('disabled')) { e.preventDefault(); return; }
        var stepId = $(this).closest('.step-item').data('step');
        if (!stepId) return;
        e.preventDefault();
        localStorage.setItem('current_step', stepId);
        restoreCurrentStep(stepId);
    });

    var userId = urlParams.get('id');
    localStorage.setItem('iduser', userId || '');
    var idUserEl = document.getElementById('id_user');
    if (idUserEl && userId) idUserEl.value = userId;

    $('input[name="name_parent"]').on('input', function() {
        var selectedOption = $(this).val();
        var selectedId = $('#parentList option[value="' + selectedOption + '"]').data('id');
        $('#parent_id').val(selectedId);
        var parentType = $(this).closest('.modal').attr('id').replace('add_', '');
        if (typeof selectedParents !== 'undefined') selectedParents[parentType] = selectedId;
    });

    function resetParentSelection(parentType) {
        if (typeof selectedParents !== 'undefined') selectedParents[parentType] = null;
        if (typeof updateSelectedParentsField === 'function') updateSelectedParentsField();
    }
    $('#searchfather, #searchmother, #searchguardian').on('change', function() {
        var parentType = $(this).attr('id').replace('search', '').toLowerCase();
        if ($(this).val() === 'no') resetParentSelection(parentType);
    });

    var step1RequiredFields = [
        { name: 'classroom', label: 'Classroom' },
        { name: 'gender', label: 'Gender' },
        { name: 'last_name', label: 'Last Name' },
        { name: 'first_name', label: 'First Name' },
        { name: 'birthday', label: 'Birthday' },
        { name: 'birth_city', label: 'Birth City' },
        { name: 'birth_country', label: 'Birth Country' },
        { name: 'nationality', label: 'Nationality' },
        { name: 'first_lang', label: 'First Language' },
        { name: 'photo', label: 'ID photo' },
        { name: 'birth_certif', label: 'Photocopy of birth certificate/Passport' },
        { name: 'previous_academic', label: 'Previous Academic Reports' },
        { name: 'transfer_certif', label: 'Transfer Certificate/Exams Results' },
        { name: 'updated_vacci', label: 'Updated Vaccination Records' }
    ];

    function validateStep1Required($form) {
        var moreInfo = document.getElementById('more_info');
        var onlyClassroom = moreInfo && moreInfo.hidden;
        var missing = [];
        $form.find('.is-invalid').removeClass('is-invalid');
        step1RequiredFields.forEach(function(f) {
            if (onlyClassroom && f.name !== 'classroom') return;
            var el = $form.find('[name="' + f.name + '"]')[0];
            if (!el) return;
            var isEmpty = false;
            if (el.type === 'file') {
                var hasExisting = $form.attr('data-has-file-' + f.name) === '1';
                isEmpty = (!el.files || el.files.length === 0) && !hasExisting;
            } else if (el.tagName === 'SELECT') {
                isEmpty = !el.value || el.value.trim() === '';
            } else {
                isEmpty = !el.value || el.value.trim() === '';
            }
            if (isEmpty) missing.push({ name: f.name, label: f.label });
        });
        return missing;
    }

    $('.next-step').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var nextStep = $btn.data('next');
        var currentStep = $btn.data('current');
        var currentForm = $btn.closest('form');
        localStorage.setItem('current_step', nextStep);

        if (currentForm.attr('id') === 'form-step1') {
            var missing = validateStep1Required(currentForm);
            if (missing.length > 0) {
                var $errBlock = $('#step1-validation-errors');
                var $list = $('#step1-validation-list');
                $list.empty();
                missing.forEach(function(m) {
                    $list.append('<li><strong>' + m.label + '</strong>: This field is required.</li>');
                    currentForm.find('[name="' + m.name + '"]').addClass('is-invalid');
                });
                $errBlock.removeClass('d-none');
                $('#step1').get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }
        }

        var btnOriginalHtml = $btn.html();
        $btn.prop('disabled', true).addClass('admission-next-loading').html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Loading...');

        function resetNextButton() {
            $btn.prop('disabled', false).removeClass('admission-next-loading').html(btnOriginalHtml);
        }

        var ajaxOpts = {
            url: currentForm.attr('action'),
            type: currentForm.attr('method'),
            success: function(data) {
                if (data && data.code_student) {
                    localStorage.setItem('code_student', data.code_student);
                    $('.code_student').val(data.code_student);
                    var nextUrl = new URL(window.location.href);
                    nextUrl.searchParams.delete('new');
                    nextUrl.searchParams.set('code_student', data.code_student);
                    window.history.replaceState({}, '', nextUrl.toString());
                }
                $('#step1-validation-errors').addClass('d-none');
                $('#step2-validation-errors').addClass('d-none');
                $('#step3-validation-errors').addClass('d-none');
                $('#step5-validation-errors').addClass('d-none');
                currentForm.find('.is-invalid').removeClass('is-invalid');
                localStorage.setItem('current_step', nextStep);
                restoreCurrentStep(nextStep);
            },
            error: function(xhr) {
                var data = xhr.responseJSON || {};
                var errors = data.errors || {};
                var messageDetail = data.message_detail || data.message;
                if (data && data.code_student) {
                    localStorage.setItem('code_student', data.code_student);
                    $('.code_student').val(data.code_student);
                }

                function showFormErrors(errBlockId, listId) {
                    var $errBlock = $('#' + errBlockId);
                    var $list = $('#' + listId);
                    $list.empty();
                    if (messageDetail) {
                        var lines = messageDetail.split(/\n/);
                        lines.forEach(function(line) {
                            line = line.replace(/^•\s*/, '').trim();
                            if (line) $list.append('<li>' + line + '</li>');
                        });
                    }
                    if (Object.keys(errors).length > 0 && $list.children().length === 0) {
                        $.each(errors, function(field, msgs) {
                            $list.append('<li><strong>' + field + '</strong>: ' + (Array.isArray(msgs) ? msgs.join(' ') : msgs) + '</li>');
                        });
                    }
                    $errBlock.removeClass('d-none');
                    currentForm.find('.is-invalid').removeClass('is-invalid');
                    $.each(errors, function(field) {
                        currentForm.find('[name="' + field + '"]').addClass('is-invalid');
                    });
                    $('#' + currentStep).get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                if (currentForm.attr('id') === 'form-step1' && (Object.keys(errors).length > 0 || messageDetail)) {
                    showFormErrors('step1-validation-errors', 'step1-validation-list');
                } else if (currentForm.attr('id') === 'form-step2' && (Object.keys(errors).length > 0 || messageDetail)) {
                    showFormErrors('step2-validation-errors', 'step2-validation-list');
                } else if (currentForm.attr('id') === 'form-step3' && (Object.keys(errors).length > 0 || messageDetail)) {
                    showFormErrors('step3-validation-errors', 'step3-validation-list');
                } else if (currentForm.attr('id') === 'form-step5' && (Object.keys(errors).length > 0 || messageDetail)) {
                    showFormErrors('step5-validation-errors', 'step5-validation-list');
                } else {
                    var msg = data.message || xhr.statusText || 'An error occurred. Please try again.';
                    alert('Error: ' + msg);
                }
            },
            complete: function() {
                resetNextButton();
            }
        };

        if (currentForm.attr('enctype') === 'multipart/form-data') {
            var formData = new FormData(currentForm[0]);
            ajaxOpts.data = formData;
            ajaxOpts.processData = false;
            ajaxOpts.contentType = false;
        } else {
            ajaxOpts.data = currentForm.serialize();
        }

        $.ajax(ajaxOpts);
    });

    $('#form-step5').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $err = $('#step5-validation-errors');
        $err.addClass('d-none').text('');
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function(data) {
                if (data && data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else if (data && data.success) {
                    window.location.href = '{{ url("/index") }}';
                }
            },
            error: function(xhr) {
                var data = xhr.responseJSON || {};
                var msg = data.message_detail || data.message || 'Please accept all commitments and try again.';
                $err.text(msg).removeClass('d-none');
                $('#step5').get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    $('.prev-step').on('click', function(e) {
        e.preventDefault();
        var prevStep = $(this).data('prev');
        localStorage.setItem('current_step', prevStep);
        restoreCurrentStep(prevStep);
    });

    function generateCode(length) {
        length = length || 8;
        var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var result = '';
        for (var i = 0; i < length; i++) result += chars.charAt(Math.floor(Math.random() * chars.length));
        return result;
    }
    });
})();
</script>
@endsection
