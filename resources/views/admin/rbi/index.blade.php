@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold mb-3">RBI Update Form</h4>
        <div class="card shadow-sm card-custom mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search by name or RBI No...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option>All Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option>All Status</option>
                            <option>Single</option>
                            <option>Married</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option>Sort by RBI No.</option>
                            <option>Sort by Last Name</option>
                            <option>Sort by Age</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="#" class="btn btn-new w-100">
                            + NEW
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <p class="small text-muted">Showing 5 of 5 records</p>

        <div class="card shadow-sm card-custom">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="text-center">
                        <tr>
                            <th>RBI NO.</th>
                            <th>NAME</th>
                            <th>SEX</th>
                            <th>AGE</th>
                            <th>HOUSEHOLD</th>
                            <th>ROLE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        <tr>
                            <td>0001</td>
                            <td>MERRICK GASPAR</td>
                            <td>3x/day</td>
                            <td>67</td>
                            <td>HH-001</td>
                            <td>BREEDER</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">VIEW</a>
                                <a href="#" class="btn btn-warning btn-sm">EDIT</a>
                            </td>
                        </tr>

                        <tr>
                            <td>0001</td>
                            <td>MERRICK GASPAR</td>
                            <td>3x/day</td>
                            <td>67</td>
                            <td>HH-001</td>
                            <td>BREEDER</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">VIEW</a>
                                <a href="#" class="btn btn-warning btn-sm">EDIT</a>
                            </td>
                        </tr>

                        <tr>
                            <td>0001</td>
                            <td>MERRICK GASPAR</td>
                            <td>3x/day</td>
                            <td>67</td>
                            <td>HH-001</td>
                            <td>BREEDER</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">VIEW</a>
                                <a href="#" class="btn btn-warning btn-sm">EDIT</a>
                            </td>
                        </tr>

                        <tr>
                            <td>0001</td>
                            <td>MERRICK GASPAR</td>
                            <td>3x/day</td>
                            <td>67</td>
                            <td>HH-001</td>
                            <td>BREEDER</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">VIEW</a>
                                <a href="#" class="btn btn-warning btn-sm">EDIT</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
