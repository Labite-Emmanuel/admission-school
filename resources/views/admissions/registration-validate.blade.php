<?php $page = 'registration-validate'; ?>
@extends('layout.mainlayout')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <!-- Page Header -->
            <div class="col-md-12">
                <div class="d-md-flex d-block align-items-center justify-content-between mb-3">
                    <div class="my-auto mb-2">
                        <h3 class="page-title mb-1">Details Registration</h3>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{url('index')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{url('students')}}">HRM</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Staff Details</li>
                            </ol>
                        </nav>
                    </div>

                </div>

            </div>
            <!-- /Page Header -->
            <div class="col-xxl-3 col-lg-4 theiaStickySidebar">
                <div class="card border-white">
                    <div class="card-header">
                        <div class="d-flex align-items-center  row-gap-3">
                            <div
                                class="d-flex align-items-center justify-content-center avatar avatar-xxl border border-dashed me-2 flex-shrink-0 text-dark frames">
                                <img src="{{URL::asset('build/img/profiles/avatar-27.jpg')}}" class="img-fluid" alt="img">
                            </div>
                            <div>
                                <span class="badge badge-soft-success d-inline-flex align-items-center mb-1"><i
                                        class="ti ti-circle-filled fs-5 me-1"></i>Active</span>
                                <h5 class="mb-1">{{$studinfo->nom}} {{$studinfo->prenom}}</h5>
                                <p class="text-primary m-0">AD1256589</p>
                                <!-- <p class="p-0">Joined On : 10 Mar 2024</p> -->
                            </div>
                        </div>
                    </div>
                    <div class="card-header" style="background-color: #00284c;">
                        <h5 style="color: #fafbfcff;">Student Details</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-6 fw-normal mb-3">Gender</dt>
                            <dd class="col-6 text-dark mb-3">{{$studinfo->sexe}}</dd>
                            <dt class="col-6 fw-normal mb-3">Birthday</dt>
                            <dd class="col-6 text-dark mb-3">{{$studinfo->birthday}}</dd>
                            <dt class="col-6 fw-normal mb-3">Birth city</dt>
                            <dd class="col-6 text-dark mb-3">{{$studinfo->birth_city}}</dd>
                            <dt class="col-6 fw-normal mb-3">Birth country</dt>
                            <dd class="col-6 text-dark mb-3">{{$studinfo->birth_country}}</dd>
                            <dt class="col-6 fw-normal mb-3">First Language</dt>
                            <dd class="col-6 text-dark mb-3">{{$studinfo->first_lang}}</dd>
                            <dd class="col-6 fw-normal mb-3">Nationnality</dd>
                            <dd class="col-6 text-dark mb-3">{{$studinfo->nationality}}</dd>
                        </dl>
                    </div>
                </div>
                <!-- <div class="card border-white">
                    <div class="card-body">
                        <h5 class="mb-3">Primary Contact Info</h5>
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-md bg-light-300 rounded me-2 flex-shrink-0 text-default"><i class="ti ti-phone"></i></span>
                            <div>
                                <span class="fs-12 mb-1">Phone Number</span>
                                <p class="text-dark">+1 46548 84498</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-md bg-light-300 rounded me-2 flex-shrink-0 text-default"><i class="ti ti-mail"></i></span>
                            <div>
                                <span class="fs-12 mb-1">Email Address</span>
                                <p class="text-dark">jan@example.com</p>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="col-xxl-6 d-flex"> -->
                <form action="{{url('admission-validate')}}" method="get">
                    <div class="card flex-fill">
                        <div class="card-header" style="background-color: #00284c;">
                            <h5 style="color: #fafbfcff;">Admission Form</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-6 fw-normal mb-3">Class Required :</dt>
                                <dd class="col-6 fw-normal mb-3">{{$studinfo->class_current}}</dd>
                                <dt class="col-6 fw-normal mb-3"></dt>
                                <dd class="col-6 fw-normal mb-3"></dd>
                                <dt class="col-6 fw-normal mb-3">Class Validated</dt>
                                <dd class="col-6 text-dark mb-3">
                                    <input type="hidden" name="code_academic" value="{{$studinfo->code}}">
                                    <select name="classroom" class="select" required>
                                        <option value="" >Select Class</option>
                                        @foreach($classes as $classe)
                                        <option value="{{$classe->name_classe}}">{{$classe->name_classe}}</option>
                                        @endforeach
                                    </select>
                                </dd>
                            </dl>
                        </div>
                    </div>
                <!-- </div> -->
                <!-- <div class="col-xxl-6 d-flex"> -->
                    <div class="card flex-fill">
                        <div class="card-header" style="background-color: #00284c;">
                            <h5 style="color: #fafbfcff;">Admission Validation</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-6 fw-normal mb-3">
                                    <button type="submit" class="btn btn-danger">Reject Admission</button>
                                </dt>
                                <dd class="col-6 text-dark mb-3">
                                    <button class="btn btn-primary">Validate Admission</button>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </form>
                <!-- </div> -->

            </div>

            <div class="col-xxl-9 col-lg-8">
                <div class="row">

                    <!-- Address -->
                    <div class="col-xxl-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header" style="background-color: #00284c;">
                                <h5 style="color: #fafbfcff;">Medical Detail</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-6 fw-normal mb-3">Blood Group</dt>
                                    <dd class="col-6 text-dark mb-3">{{$medicalinfo->blood_group ?? 'Non spécifié'}}</dd>
                                    <dt class="col-6 fw-normal mb-3">Doctor name</dt>
                                    <dd class="col-6 text-dark mb-3">{{$medicalinfo->doctor_name ?? 'Non spécifié'}}</dd>
                                    <dt class="col-6 fw-normal mb-3">Any medical recommendations, concerns or needs</dt>
                                    <dd class="col-6 text-dark mb-3">{{$medicalinfo->any_recommendation ?? 'Non spécifié'}}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <!-- /Address -->

                    <!-- Documents -->
                    <div class="col-xxl-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header" style="background-color: #00284c;">
                                <h5 style="color: #fafbfcff;">Emergency & pickup contacts</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-3">
                                    <dd class="col-6 fw-normal mb-3">Emergency 1</dd>
                                    <dd class="col-6 text-dark mb-3">{{ ($emmergeinfo->emergency_name1 ?? '').' / '.($emmergeinfo->emergency_contact1 ?? '').' / '.($emmergeinfo->emergency_relation1 ?? 'Non spécifié') ?: '' }}</dd>
                                    <dt class="col-6 fw-normal mb-3">Person 1 to pickup child</dt>
                                    <dd class="col-6 text-dark mb-3">{{ ($emmergeinfo->pickup_name1 ?? ''). ' / '.($emmergeinfo->pickup_contact1 ?? '').' / '.($emmergeinfo->pickup_relation1 ?? 'Non spécifié') ?: '' }}</dd>
                                    <dt class="col-6 fw-normal mb-3">Emergency 2</dt>
                                    <dd class="col-6 text-dark mb-3">{{ ($emmergeinfo->emergency_name2 ?? '').' / '.($emmergeinfo->emergency_contact2 ?? '').' / '.($emmergeinfo->emergency_relation2 ?? 'Non spécifié') ?: '' }}</dd>
                                    <dt class="col-6 fw-normal mb-3">Person 2 to pickup child</dt>
                                    <dd class="col-6 text-dark mb-3">{{ ($emmergeinfo->pickup_name2 ?? ''). ' / '.($emmergeinfo->pickup_contact2 ?? '').' / '.($emmergeinfo->pickup_relation2 ?? 'Non spécifié') ?: '' }}</dd>
                                    <dt class="col-6 fw-normal mb-3">Emergency 3</dt>
                                    <dd class="col-6 text-dark mb-3">{{ ($emmergeinfo->emergency_name3 ?? '').' / '.($emmergeinfo->emergency_contact3 ?? '').' / '.($emmergeinfo->emergency_relation3 ?? '') ?: 'Non spécifié' }}</dd>
                                    <dt class="col-6 fw-normal mb-3">Person 1 to pickup child</dt>
                                    <dd class="col-6 text-dark mb-3">{{ ($emmergeinfo->pickup_name3 ?? ''). ' / '.($emmergeinfo->pickup_contact3 ?? '').' / '.($emmergeinfo->pickup_relation3 ?? 'Non spécifié') ?: '' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <!-- /Documents -->

                    <!-- Bank Details -->
                    <div class="col-xxl-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header" style="background-color: #00284c;">
                                <h5 style="color: #fafbfcff;">Parents details</h5>
                            </div>
                            <div class="card-body pb-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h4>Father</h3><br>
                                            <dl class="row mb-3">
                                                <dt class="col-6 fw-normal mb-3">Name</dt>
                                                <dd class="col-6 text-dark mb-3">{{ ($motherinfos->fist_name ?? '').' '.($motherinfos->last_name ?? 'Non spécifié') }}</dd>
                                                <!-- <dd class="col-6 text-dark mb-3">{{$fatherinfos->fist_name ?? 'Non spécifié'}} {{$fatherinfos->last_name ?? 'Non spécifié'}}</dd> -->
                                                <dt class="col-6 fw-normal mb-3">City/Country</dt>
                                                <dd class="col-6 text-dark mb-3">{{ ($fatherinfos->city ?? '').' / '.($fatherinfos->country ?? 'Non spécifié') }}</dd>
                                                <dt class="col-6 fw-normal mb-3">Nationality</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->nationality ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Email</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->email ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Phone</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->phone ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Whatsapp Phone</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->whatsapp_phone ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Post code</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->postal_code ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Adress</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">SMS/Email language</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Occupation</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->occupation ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Entreprise</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->entreprise ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Responsible of School Fees</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                            </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <h4>Mother</h3><br>
                                            <dl class="row mb-3">
                                                <dt class="col-6 fw-normal mb-3">Name</dt>
                                                <dd class="col-6 text-dark mb-3">{{ ($motherinfos->fist_name ?? '').' '.($motherinfos->last_name ?? 'Non spécifié') }}</dd>
                                                <dt class="col-6 fw-normal mb-3">City/Country</dt>
                                                <dd class="col-6 text-dark mb-3">{{ ($motherinfos->city ?? '').' / '.($motherinfos->country ?? 'Non spécifié') }}</dd>
                                                <dt class="col-6 fw-normal mb-3">Nationality</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->nationality ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Email</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->email ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Phone</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->phone ?? 'Non spécifié'}}</dd>
                                               <dt class="col-6 fw-normal mb-3">Whatsapp Phone</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->whatsapp_phone ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Post code</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->postal_code ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Adress</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">SMS/Email language</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Occupation</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->occupation ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Entreprise</dt>
                                                <dd class="col-6 text-dark mb-3">{{$motherinfos->entreprise ?? 'Non spécifié'}}</dd>
                                                <dt class="col-6 fw-normal mb-3">Responsible of School Fees</dt>
                                                <dd class="col-6 text-dark mb-3">{{$fatherinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                            </dl>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Guardian</h3><br>
                                        <dl class="row mb-3">
                                            <dt class="col-6 fw-normal mb-3">Name</dt>
                                            <dd class="col-6 text-dark mb-3">{{ ($guardianinfos->fist_name ?? '').' '.($guardianinfos->last_name ?? 'Non spécifié') }}</dd>
                                            <!-- <dd class="col-6 text-dark mb-3">{{$guardianinfos->fist_name ?? 'Non spécifié'}} {{$guardianinfos->last_name ?? 'Non spécifié'}}</dd> -->
                                            <dt class="col-6 fw-normal mb-3">City/Country</dt>
                                            <dd class="col-6 text-dark mb-3">{{ ($guardianinfos->city ?? 'Non spécifié').' / '.($guardianinfos->country ?? 'Non spécifié') }}</dd>
                                            <dt class="col-6 fw-normal mb-3">Nationality</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->nationality ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Email</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->email ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Phone</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->phone ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Whatsapp Phone</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->whatsapp_phone ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Post code</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->postal_code ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Adress</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">SMS/Email language</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Occupation</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->occupation ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Entreprise</dt>
                                            <dd class="col-6 text-dark mb-3">{{$guardianinfos->entreprise ?? 'Non spécifié'}}</dd>
                                            <dt class="col-6 fw-normal mb-3">Responsible of School Fees</dt>
                                            <dd class="col-6 text-dark mb-3">{{$fatherinfos->responsible_of_school_fees ?? 'Non spécifié'}}</dd>
                                        </dl>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Bank Details -->

                    <!-- Other Info -->
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #00284c;">
                                <h5 style="color: #fafbfcff;">Files</h5>
                            </div>
                            <div class="card-body pb-1">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h4>Passport</h3><br>
                                        <a href="#"><i class="fa fa-download"></i> Download Passport file</a>
                                    </div>
                                    <div class="col-md-3">
                                        <h3>Academic</h3><br>
                                        <a href="#"><i class="fa fa-download"></i> Download academic file</a>
                                    </div>
                                    <div class="col-md-3">
                                        <h4>Exams</h3><br>
                                        <a href="#"><i class="fa fa-download"></i> Download exams file</a>
                                    </div>
                                    <div class="col-md-3">
                                        <h3>Vaccination</h3><br>
                                        <a href="#"><i class="fa fa-download"></i> Download Vacc file</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Other Info -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Wrapper -->

@component('components.modal-popup')
@endcomponent
@endsection