@extends('layouts.app')

@section('content')
<style>
     a:hover {
        color: rgb(27, 108, 138);
    }
    a{
       font-family: 'Poppins', sans-serif;
        font-weight:500;
        color:#8B7eff;
        font-size:13px;
    }

    .city {
       font-family: 'Poppins', sans-serif;
        font-weight:600;
        color:#282833;
        font-size:13px;
    }

    .custom-message-modal {
        width: 100%;
        background: #29292960;
    }

    .sticky-btn {
        position: fixed;
        bottom: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        background-color: #ff0000ff;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around" id="watch">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>

    <div class="text-end col-lg-6 ">
         <b><a href="/dashboard">Dashboard</a> > <a class="city" href="/address">Address</a></b>
    </div>

    <div class="mt-2 mb-2 col-lg-12">
        <div class="d-flex justify-content-end">
           <a href="{{ route('admin.influencer_add_form') }}">
                 <button class="btn btn-add px-4" type="button">Add Influencer</button>
            </a>
        </div>
    </div>
</div>


<!-- TABLE -->
<div class="row body-sec px-5">
<div class="bg-white pt-3 col-lg-12">
<div class="table-sec rounded-bottom-4 mb-5">

<table id="cityTable" class="table table-bordered pt-2">

<thead>
<tr class="rounded-top-4">
<th class="text-start">S.No</th>
<th class="text-start">Name</th>
<th class="text-start">Reference ID</th>
<th class="text-start">Email</th>
<th class="text-start">Date & Time</th>
<th class="text-start">Information</th>
<th class="text-start">Affiliate Link</th>
<th class="text-start">Action</th>
</tr>
</thead>

<tbody>

@foreach ($influencers as $row)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $row->full_name }}</td>

<td>{{ $row->reference_id }}</td>

<td>{{ $row->email }}</td>

<!-- TIMESTAMP -->
<td>
{{ $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '-' }}
</td>

<td>
<a class="btn view-btn" title="View" href="{{ route('admin.influencer_view', $row->id) }}">
<i class="bi bi-eye-fill" style="color:#000 !important;"></i>
</a>
</td>

<td style="width:10%;">

<button class="btn text-white btn-sm view-links"
title="Link"
data-id="{{ $row->id }}"
data-name="{{ $row->full_name }}"
data-signup-url="https://innerpece.com/signup?ref={{ $row->reference_id }}-{{ substr($row->full_name,0,4) }}">

<i class="fa fa-link" style="color:#008000 !important;"></i>

</button>

</td>

<td>

<a href="{{ route('admin.influencer_edit_form', $row->id) }}" title="Edit">
<span class="fa-stack">
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>

<a href="javascript:void(0);" class="table-link danger delconfirm"
title="Delete"
data-row_id="{{ $row->id }}"
data-act_url="{{ route('admin.influencer_delete') }}"
data-csrf_token="{{ csrf_token() }}">

<span class="fa-stack">
<i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color:red !important;"></i>
</span>

</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>
</div>


<!-- Scroll Button -->
<button class="sticky-btn" id="myBtn">
<a href="#watch"><i class="bi bi-caret-up-square text-white"></i></a>
</button>

@endsection


@section('scripts')

<script>

$(document).ready(function(){

$('#cityTable').DataTable({

pageLength:10,
lengthChange:true,
ordering:true,
searching:true,

language:{
emptyTable:"No records found",
searchPlaceholder:"Search influencers...",
search:""
},

columnDefs:[
{ orderable:true, targets:[0,3] }
]

});

});


/* Affiliate Links Modal */
$(document).on('click','.view-links',function(){

const influencerId=$(this).data('id');
const influencerName=$(this).data('name');
const signupUrl=$(this).data('signup-url');
const shortName=influencerName.substring(0,4);

$('#affiliateLinksLabel').html(`Affiliate Links for ${influencerName} <a href="${signupUrl}" target="_blank">${signupUrl}</a>`);

$('#links-container').html(`
<p><strong>Signup URL:</strong>
<a href="${signupUrl}" target="_blank">${signupUrl}</a></p>
<p>Loading affiliate links...</p>
`);

$.ajax({

url:`/admin/influencer/${influencerId}/affiliate-links`,
method:'GET',

success:function(response){

if(response.status==='1'){

const links=response.data;

const linksHtml=links.map(link =>
`<p><strong>${link.title}:</strong>
<a href="${link.link}-${shortName}" target="_blank">${link.link}-${shortName}</a></p>`
).join('');

$('#links-container').html(linksHtml);

}else{

$('#links-container').html('<p>No affiliate links found.</p>');

}

},

error:function(){

$('#links-container').html('<p>Error fetching affiliate links.</p>');

}

});

$('#affiliateLinksModal').modal('show');

});

</script>

@endsection