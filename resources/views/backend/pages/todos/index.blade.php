
@extends('backend.layouts.master')

@section('title')
Admins - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Todos</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>All Todos</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">Todos List</h4>
                    <p class="float-right mb-2">
                        @if (Auth::guard('admin')->user()->can('todo.edit'))
                            <a class="btn btn-primary text-white" href="{{ route('admin.todos.create') }}">Create New Todo</a>
                        @endif
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                       
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th width="10%">Title</th>
                                    <th width="10%">Description</th>
                                    <th width="40%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($todos as $todo)
                               <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $todo->title }}</td>
                                    <td>{{ $todo->description }}</td>

                                    <td>
                                        @if (Auth::guard('admin')->user()->can('todo.updateStatus'))
                                            <form action="{{ route('admin.todos.updateStatus', $todo->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="select2" onchange="this.form.submit()">
                                                    <option value="open" {{ $todo->status == 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="in_progress" {{ $todo->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="completed" {{ $todo->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </form>
                                        @else
                                      
                                           <span class="badge bagde-sm"> {{ ucfirst($todo->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::guard('admin')->user()->can('todo.edit'))
                                            <a class="btn btn-success text-white" href="{{ route('admin.todos.edit', $todo->id) }}">Edit</a>
                                        @endif
                                        
                                        @if (Auth::guard('admin')->user()->can('todo.delete'))
                                        <a class="btn btn-danger text-white" href="{{ route('admin.todos.destroy', $todo->id) }}"
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $todo->id }}').submit();">
                                            Delete
                                        </a>
                                        <form id="delete-form-{{ $todo->id }}" action="{{ route('admin.todos.destroy', $todo->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
        
    </div>
</div>
@endsection


@section('scripts')
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

     <script>
         /*================================
        datatable active
        ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true
            });
        }

     
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection