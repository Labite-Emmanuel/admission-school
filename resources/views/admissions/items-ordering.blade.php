<?php $page = 'items-ordering'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper" style="margin-top: 20px;">
    <div class="content content-two">
        <div class="" >
            <h1 style="font-size: 30px;">Admission List</p>
        </div><br>

        <table class="table datatable">
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
                @foreach($studitemsordes as $studitemsorde)
                <?php $i++; ?>
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$studitemsorde->admission_date}}</td>
                    <td>{{$studitemsorde->admission_date}}</td>
                    <td>{{$studitemsorde->academicyear}}</td>
                    <td>{{$studitemsorde->classroom}}</td>
                    <td></td>
                    <td>{{$studitemsorde->first_name}} {{$studitemsorde->last_name}}</td>
                    <td style="color: orange;">{{$studitemsorde->registration}}</td>
                    <td>
                        <a href="/ordering-validate/{{$studitemsorde->classroom}}/{{$studitemsorde->code}}"><button class="btn btn-warning fa fa-file"></button></a>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-light fa fa-eye"></button>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="btn btn-danger ti ti-trash"></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>


@endsection