@php
    $codeStudent = $step1info->code_student ?? session('studData.codeStudent') ?? old('code_student') ?? '';
    $step6info = $step6info ?? $step5info ?? null;
    $step4father = $step4father ?? null;
    $step4mother = $step4mother ?? null;
    $step4guardian = $step4guardian ?? null;
@endphp

<style>
    .form-check-input.form-control-saved { accent-color: #0d6efd; }
    .form-check-label.form-control-saved { color: #0d6efd; font-weight: 500; }
</style>

<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex align-items-center">
            <span class="bg-white avatar avatar-sm me-2 text-gray-7 flex-shrink-0">
                <i class="ti ti-info-square-rounded fs-16"></i>
            </span>
            <h4 class="text-dark">Step 6 - Commitments and Agreements</h4>
        </div>
    </div>
    <div id="step6-validation-errors" class="alert alert-danger d-none mx-3 mt-2" role="alert"></div>
    <div class="card-body pb-1">
        <input type="hidden" name="code_student" class="code_student" value="{{ $codeStudent }}">
        <input type="hidden" name="id_us" value="{{ session('user.id') }}">
        <div class="col-12" id="commitments-form">

            <!-- Section 1: Rules and Regulations -->
            <div class="mb-4">
                <h2 class="mb-3">COMMITMENTS</h2>
                <p class="mb-4">
                    In accordance with the <a href="https://scholarsapp.iesaciv.com/assets/loi/loi-2013-546-30-juillet-2013-transactions-electroniques.pdf" 
                    target="_blank" download style="color: red;">law N&#186; 2013-546 of 30-July-2013</a> referring to electronic transactions, 
                    all digital signatures has equal legal value as handwritten signatures. As such, the digital 
                    signatories abide by commitments herein after contracted.
                </p>

                <h2 class="mb-3">RULES AND REGULATIONS</h2>
                <p>1. Pupils are expected to wear the prescribed school uniform and to be clean and tidy at all times to and from school. Any modification to the uniform is not allowed.</p>
                <p>2. Pupils must be courteous and respectful to everyone.</p>
                <p>3. Pupils must be on their best behaviour even outside school.</p>
                <p>4. School does not accept bullying of any kind by any pupils.</p>
                <p>5. Pupils should complete their homework neatly and hand it in punctually.</p>
                <p>6. Pupils must be punctual and regular for school and school activities.</p>
                <p>7. Absence from classes, exams and other school activities must be covered by a medical certificate or a letter of excuse from the parent/guardian.</p>
                <p>8. Pupils must keep their classrooms and school premises clean.</p>
                <p>9. Pupils must move quietly, briskly and in an orderly manner.</p>
                <p>10. Pupils must handle school equipment and property with care.</p>
                <p>11. Pupils are not allowed to bring mobile phones or any form of electronic gadgets at school. if found on pupils, these gadgets will be confiscated and parents/guardians will be asked to claim these items from the office.</p>
                <p>12. Pupils are not allowed to bring jewels, drugs and any kind of dangerous tools (knives, sharp objects...).</p>
                <p>13. Make up must not be worn to school by pupils. Coloured nail polish and nail decorations and extensions are not permitted.</p>
                <p>14. Tinting, bleaching, colouring and any fashionable hairstyling are not allowed.</p>
                <p>15. Students should not wear any form of cosmetics, nor should there be body piercings, or unnatural body markings made with inks, paints, etc.</p>
                <p>16. Any student who violates the school rules and regulations is liable to face disciplinary action.</p>
                <p>Download the complete Rules and Regulations <a href="https://scholarsapp.iesaciv.com/assets/pdf/Rules & Regulations.pdf" download style="color: red;">here</a></p>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input {{ ($step6info && $step6info->aggree_one) ? 'form-control-saved' : '' }}" 
                           id="aggree_one" style="width: 24px; height: 24px;" name="aggree_one" value="1" {{ ($step6info && $step6info->aggree_one) ? 'checked' : '' }} required>
                    <label class="form-check-label {{ ($step6info && $step6info->aggree_one) ? 'form-control-saved' : '' }}" for="aggree_one">
                        <span style="color: #00284c; font-weight: 500; font-size: 24px;">&nbsp; I agree <span class="text-danger">*</span></span>
                    </label>
                </div>
            </div>

            <!-- Section 2: Home School Agreement -->
            <div class="mb-4">
                <h2 class="mb-3">HOME SCHOOL AGREEMENT</h2>
                <p>As a parent, I will:</p>
                <p>1. Take and active interest in all aspects of my child's school life;</p>
                <p>2. See that my child attends school regulary, on time and properly equipped;</p>
                <p>3. Support my child's participation in all school events and projects;</p>
                <p>4. Communicate to school all relevant information which may affect my child's work or behaviour ;</p>
                <p>5. Notify the school if, for any reason, my child cannot attend;</p>
                <p>6. Encourage my child to follow the school's behaviour policy and support associated action taken by the school ;</p>
                <p>7. Support the school's policy on homework, provide suitable facilities at home, and encourage my child to make the required effort ;</p>
                <p>8. Sign home assignments and class tests regulary;</p>
                <p>9. Replace all school property damaged by my child including payment of labour charges when applicable</p>
                <p>Respect fee's payment deadlines</p>
                <p>Do my best to attend parents' evenings and other meetings at which my presence requested.</p>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input {{ ($step6info && $step6info->aggree_two) ? 'form-control-saved' : '' }}" 
                           id="aggree_two" style="width: 24px; height: 24px;" name="aggree_two" value="1" {{ ($step6info && $step6info->aggree_two) ? 'checked' : '' }} required>
                    <label class="form-check-label {{ ($step6info && $step6info->aggree_two) ? 'form-control-saved' : '' }}" for="aggree_two">
                        <span style="color: #00284c; font-weight: 500; font-size: 24px;">&nbsp; I agree <span class="text-danger">*</span></span>
                    </label>
                </div>
            </div>

            <!-- Section 3: School Fees Policies -->
            <div class="mb-4">
                <h2 class="mb-3">SCHOOL FEES POLICIES</h2>
                <p>1. Fees once paid are not refundable.</p>
                <p>2. Payment should be made by cash/ Bank Transfer/Cheque (Cheque should be in the favour of "International English School of Abidjan or IESA")</p>
                <p>3. In case of a bounced cheque, penalty of 20% will be charged.</p>
                <p>4. For all cash payments, please provide the relevant amount of tax:</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>AMOUNT PAID (FCFA)</th>
                            <th>TAX FEE (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>5,001—100,000</td><td>100</td></tr>
                        <tr><td>100,001—500,000</td><td>500</td></tr>
                        <tr><td>500,001—1,000,000</td><td>1,000</td></tr>
                        <tr><td>1,000,001—5,000,000</td><td>2,000</td></tr>
                        <tr><td>More than 5,000,000</td><td>5,000</td></tr>
                    </tbody>
                </table>
                <p>5. Tuition Fees are paid in full or in instalments according to the payment plan agreed with the school. If paid in installments, 50% of the tuition has to be paid before the child can start school.</p>
                <p>6. Transportation, Canteen and Extra activities fees are fixed, irrespective of working days per school term.</p>
                <p>7. The payment per term of Transportation and Canteen fees is equal to the total sum of the months in the term.</p>
                <p>8. The payment of Transportation, Canteen and Extra activities fees for one term has to be made at time subscription of these services and before the beginning of each term for all renewal. In case of non-payment the service will be discontinued.</p>
                <p>9. Parent must necessary respect payment deadlines.</p>
                <p>10. Upon non-payment of tuition fees, a reminder letter, SMS or email will be sent. If no payment is received within 5 days, penalty of 10% will be charged and we'll refuse the pupil entry to the school premises.</p>
                <p>11. Further non-payment within completion of 15 days from the 1st due date will put the child off the class roll.</p>
                <p>12. All school fees and terms and conditions mentioned above are valid at the time of admission. However, the school reserves the right to make changes to the fees structure, terms & conditions and/or ancillary from time to time.</p>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input {{ ($step6info && $step6info->aggree_three) ? 'form-control-saved' : '' }}" 
                           id="aggree_three" style="width: 24px; height: 24px;" name="aggree_three" value="1" {{ ($step6info && $step6info->aggree_three) ? 'checked' : '' }} required>
                    <label class="form-check-label {{ ($step6info && $step6info->aggree_three) ? 'form-control-saved' : '' }}" for="aggree_three">
                        <span style="color: #00284c; font-weight: 500; font-size: 24px;">&nbsp; I agree <span class="text-danger">*</span></span>
                    </label>
                </div>
            </div>

            <!-- Section 4: Commitment of Party Responsible -->
            <div class="mb-4">
                <h2 class="mb-3">COMMITMENT OF PARTY RESPONSIBLE OF SCHOOL FEES</h2>
                <p>1. I understand and accept my financial obligation (school fees invoice(s) and all other fees) due to IESA.</p>
                <p>2. I agree to abide by the dates listed in the payments plan.</p>
                <p>3. I understand that I am responsible for enrolling for IESA's School Fees Management system and remaining in the IESA program for the entire year if I am paying on the six-month plan.</p>
                <p>4. I understand that if my child (ren) school fees account is more than 5 days in arrears, my child (ren) will be sent home until fee payments clearance is obtained.</p>
                <p>5. I understand that if my child (ren) leaves in the middle of any month, I am responsible for the entire month's payment.</p>
                <p>6. I understand that failure to meet my financial obligation could expose me to recovery procedures that may lead to prosecution.</p>
                <p>7. I understand that IESA has the right to seek legal action for collection of school fees.</p>
                <p>The party responsible for school fees will be responsible for all costs of collection, including court expenses and attorney fees.</p>
                <p>8. I understand that this commitment is in addition to IESA's Fee Policy.</p>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input {{ ($step6info && $step6info->aggree_for) ? 'form-control-saved' : '' }}" 
                           id="aggree_for" style="width: 24px; height: 24px;" name="aggree_for" value="1" {{ ($step6info && $step6info->aggree_for) ? 'checked' : '' }} required>
                    <label class="form-check-label {{ ($step6info && $step6info->aggree_for) ? 'form-control-saved' : '' }}" for="aggree_for">
                        <span style="color: #00284c; font-weight: 500; font-size: 24px;">&nbsp; I agree <span class="text-danger">*</span></span>
                    </label>
                </div>
            </div>

            <!-- Section 5: Parents Declaration -->
            <div class="mb-4">
                <h2 class="mb-3">PARENT DECLARATION</h2>
                <p>I have read all the rules, agreements and policies aforementioned.</p>
                <p>I declare that if our son/daughter/ward is granted admission, I shall abide by all the present rules, agreements and policies of the school as also those framed from time to time.</p>
                <p>I hereby authorize the school authorities to give first aid to /get medically treated our child/ward in case of any necessity.</p>
                <p>I understand that the School may obtain process and hold personal information about our child, including sensitive information, such as medical details, and I consent to this for the purposes of assessment and in order to safeguard and promote the welfare of the child.</p>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input {{ ($step6info && $step6info->aggree_five) ? 'form-control-saved' : '' }}" 
                           id="aggree_five" style="width: 24px; height: 24px;" name="aggree_five" value="1" {{ ($step6info && $step6info->aggree_five) ? 'checked' : '' }} required>
                    <label class="form-check-label {{ ($step6info && $step6info->aggree_five) ? 'form-control-saved' : '' }}" for="aggree_five">
                        <span style="color: #00284c; font-weight: 500; font-size: 24px;">&nbsp; I agree <span class="text-danger">*</span></span>
                    </label>
                </div>
            </div>

            <!-- Signature de l'engagement : noms et numéros des parents -->
            <div class="mt-4 pt-4 border-top">
                <h3 class="mb-3" style="color: #00284c; font-size: 1.1rem;">Signature of commitment</h3>
                <p class="small text-muted mb-3">The undersigned parent(s) / guardian(s) acknowledge having read and accepted the above commitments and agreements.</p>
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    @if($step4father)
                    <div class="col">
                        <div class="border rounded p-3 bg-light">
                            <div class="small text-muted text-uppercase fw-semibold mb-1">Father</div>
                            <div class="fw-medium">{{ trim(($step4father->fist_name ?? '') . ' ' . ($step4father->last_name ?? '')) }}</div>
                            <div class="small">{{ $step4father->main_mobile ?? $step4father->whatsapp_phone ?? $step4father->personnal_phone ?? '—' }}</div>
                        </div>
                    </div>
                    @endif
                    @if($step4mother)
                    <div class="col">
                        <div class="border rounded p-3 bg-light">
                            <div class="small text-muted text-uppercase fw-semibold mb-1">Mother</div>
                            <div class="fw-medium">{{ trim(($step4mother->fist_name ?? '') . ' ' . ($step4mother->last_name ?? '')) }}</div>
                            <div class="small">{{ $step4mother->main_mobile ?? $step4mother->whatsapp_phone ?? $step4mother->personnal_phone ?? '—' }}</div>
                        </div>
                    </div>
                    @endif
                    @if($step4guardian)
                    <div class="col">
                        <div class="border rounded p-3 bg-light">
                            <div class="small text-muted text-uppercase fw-semibold mb-1">Guardian</div>
                            <div class="fw-medium">{{ trim(($step4guardian->fist_name ?? '') . ' ' . ($step4guardian->last_name ?? '')) }}</div>
                            <div class="small">{{ $step4guardian->main_mobile ?? $step4guardian->whatsapp_phone ?? $step4guardian->personnal_phone ?? '—' }}</div>
                        </div>
                    </div>
                    @endif
                    @if(!$step4father && !$step4mother && !$step4guardian)
                    <div class="col-12">
                        <p class="small text-muted mb-0">No parent or guardian has been registered yet. Please complete Step 5 (Family Information) first.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
