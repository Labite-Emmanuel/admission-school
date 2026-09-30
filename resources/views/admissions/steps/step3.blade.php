@php
    $step3info = $step3info ?? (object) [];
@endphp
<style>
    .form-control.form-control-saved,
    .form-control.form-control-saved:focus {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.06);
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
    .info-block-title { font-weight: 600; color: #212529; margin-bottom: 0.75rem; font-size: 0.95rem; }
</style>
<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex align-items-center">
            <span class="bg-white avatar avatar-sm me-2 text-gray-7 flex-shrink-0">
                <i class="ti ti-brain fs-16"></i>
            </span>
            <h4 class="text-dark">Step 3 - LEARNING & BEHAVIOURAL INFORMATION</h4>
        </div>
    </div>
    <div class="card-body pb-1">
        <input type="hidden" name="code_student" class="code_student">
        <input type="hidden" name="id_us" value="{{ session('user.id') }}">

        <div class="learning-behavioural-header">
            <h5>LEARNING & BEHAVIOURAL INFORMATION *</h5>
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
                        <div class="info-block-title">BEHAVIOURAL, EMOTIONAL & DEVELOPMENTAL DIFFICULTIES</div>
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
    </div>
</div>

<script>
function toggleConditionDetails() {
    const hasCondition = document.querySelector('input[name="has_condition"]:checked');
    const details = document.getElementById('conditionDetails');
    details.style.display = (hasCondition && hasCondition.value === 'yes') ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', function() { toggleConditionDetails(); });
</script>
