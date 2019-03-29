@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Category Campaign
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($categoryCampaign, ['route' => ['categoryCampaigns.update', $categoryCampaign->id], 'method' => 'patch']) !!}

                        @include('category_campaigns.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection