{{-- 
  Master Layout: resources/views/layouts/app.blade.php
  
  Usage in any view:
    @extends('layouts.app')
    @section('title', 'Page Title')
    @section('content')
        ... your page content here ...
    @endsection
--}}

@include('layouts.header')

@include('layouts.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              @yield('breadcrumb')
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        @yield('content')

      </div>
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

@include('layouts.footer')