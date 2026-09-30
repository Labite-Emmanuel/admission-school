<?php $page = 'in-process'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper" style="margin-top: 20px;">
    <div class="content ">
       
        <div class="" >
            <h1 style="font-size: 30px;">Admission List</p>
        </div><br>

        <table class="table responsive">
            <thead class="thead-light">
                <tr>
                    <th>N°</th>
                    <th>Save Date</th>
                    <th>Admission date</th>
                    <th>Academic Year</th>
                    <th>Class Required</th>
                    <th>Admission Class</th>
                    <th>Student Full Name</th>
                    <th>status</th>
                    <th></th>
                    <th>Detail Student</th>
                    <th>Test Interview</th>
                    <th>By pass</th>
                    <th>Action</th>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach($studinprocess as $studinproces)
                <?php $i++; ?>
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$studinproces->admission_date}}</td>
                    <td>{{$studinproces->admission_date}}</td>
                    <td>{{$studinproces->academicyear}}</td>
                    <td>{{$studinproces->classroom}}</td>
                    <td></td>
                    <td>{{$studinproces->first_name}} {{$studinproces->last_name}}</td>
                    <td style="color: red;">{{$studinproces->registration}}</td>
                    <td>
                        <a href="/./registration-validate/{{$studinproces->code_parent}}"><button class="btn btn-danger">Validate Class</button></a>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-light"><i class="fa fa-eye"></i></button>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="btn btn-danger"><i class="ti ti-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>


@endsection