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
</style>

 <div class="row body-sec py-3 px-5 justify-content-around">
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


<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="bg-white pt-3 col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <table id="cityTable" class="table table-bordered pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-start"><span>S.No</span></th>
                        <th class="text-start"><span>Name</span></th>
                        <th class="text-start"><span>Reference_id</span></th>
                        <th class="text-start"><span>Email</span></th>
                        <th class="text-start"><span>Date</span></th>
                        <th class="text-start"><span>Information</span></th>
                        <th class="text-start"><span>Affiliate Link</span></th>
                        <!-- <th class="text-center"><span>Clicks</span></th> -->
                        <th class="text-start"><span>Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($influencers as $row)
                    <tr>
                        <td class="text-start">{{ $loop->iteration }}</td>
                        <td class="text-start">{{ $row->full_name }}</td>
                        <td class="text-start">{{ $row->reference_id }}</td>
                        <td class="text-start">{{ $row->email }}</td>
                        <td class="text-start">{{ \App\Helpers\DateHelper::formatDate($row->created_at) }}</td>
                        <td class="text-start">
                             <a class="btn view-btn" href="{{ route('admin.influencer_view', $row->id) }}">
                                <i class="bi bi-eye-fill" style="color:#000 !important;"></i>
                            </a>
                        </td>
                        <!-- <td class="text-center" style="font-size: small;">{{ $row->referral_code }}</td> -->
                        <td class="text-start" style="width: 10%;">
                            <button class="btn text-white btn-sm  view-links" data-id="{{ $row->id }}" data-name="{{ $row->full_name }}"
                            data-signup-url="https://innerpece.com/signup?ref={{ $row->reference_id }}-{{ substr($row->full_name, 0, 4) }}"
                           > 
                                <i class="fa fa-link" style="color:#008000 !important;" aria-hidden="true"></i>
                            </button>
                        </td>
                        <!-- <td class="text-center">
                            @if ($row->affiliate_links->isNotEmpty())
                            <ul>
                                @foreach ($row->affiliate_links as $link)
                                <li>{{ $link['click_count'] ?? 'N/A' }}</li>
                                @endforeach
                            </ul>
                            @else
                            0
                            @endif
                        </td> -->
                        <td class="text-start">
                            <a href="{{ route('admin.influencer_edit_form', $row->id) }}" class="table-edit-link">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.influencer_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color: red !important;"></i>
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

<!-- Modal -->
<div class="custom-message-modal modal fade px-10" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="viewModalLabel">Influencer Details</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
                <p><strong>Whatsapp:</strong> <span id="modalwhatsapp"></span></p>
                <p><strong>Gender:</strong> <span id="modalgender"></span></p>
                <p><strong>Age:</strong> <span id="modalage"></span></p>
                <p><strong>City:</strong> <span id="modalcity"></span></p>
                <p><strong>Instagram Name:</strong> <span id="modalinstname"></span></p>
                <p><strong>Instagram Link:</strong> <span id="modalinstlink"></span></p>
                <p><strong>LinkedIn Name:</strong> <span id="modallinkname"></span></p>
                <p><strong>LinkedIn Link:</strong> <span id="modallinklink"></span></p>
                <p><strong>Youtube Name:</strong> <span id="modalyouname"></span></p>
                <p><strong>Youtube Link:</strong> <span id="modalyoulink"></span></p>
                <p><strong>Twitter Name:</strong> <span id="modaltwittername"></span></p>
                <p><strong>Twitter Link:</strong> <span id="modaltwitterlink"></span></p>
                <p><strong>Facebook Name:</strong> <span id="modalfacename"></span></p>
                <p><strong>Facebook Link:</strong> <span id="modalfacelink"></span></p>
                <p><strong>Date & Time:</strong> <span id="modalDate"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Affiliate Links Modal -->
<div class="custom-message-modal modal fade" id="affiliateLinksModal" tabindex="-1" aria-labelledby="affiliateLinksLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="affiliateLinksLabel">Affiliate Links</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="links-container">
                    <!-- Links will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).on('click', '.view-links', function () {
    const influencerId = $(this).data('id');
    const influencerName = $(this).data('name');
    const signupUrl = $(this).data('signup-url');
    const shortName = influencerName.substring(0, 4);
    $('#affiliateLinksLabel').html(`Affiliate Links for ${influencerName} <a href="${signupUrl}" target="_blank">${signupUrl}</a>`);
    
    // Display signup URL in the modal
    $('#links-container').html(`
        <p><strong>Signup URL:</strong> <a href="${signupUrl}" target="_blank" class="track-click" data-link="${signupUrl}">${signupUrl}</a></p>
        <p>Loading affiliate links...</p>
    `);
    
    $.ajax({
        url: `/admin/influencer/${influencerId}/affiliate-links`,
        method: 'GET',
        success: function (response) {
            if (response.status === '1') {
                const links = response.data;
                const linksHtml = links
                    .map(link => `<p><strong>${link.title}:</strong> <a href="${link.link}-${shortName}" target="_blank" class="track-click" data-link="${link.link}-${shortName}">${link.link}-${shortName}</a></p>`)
                    .join('');
                $('#links-container').html(linksHtml);
            } else {
                $('#links-container').html('<p>No affiliate links found.</p>');
            }
        },
        error: function () {
            $('#links-container').html('<p>Error fetching affiliate links. Please try again.</p>');
        },
    });

    $('#affiliateLinksModal').modal('show');
});

// Track clicks on affiliate links
$(document).on('click', '.track-click', function () {
    const link = $(this).data('link');
    $.ajax({
        url: '/admin/influencer/track-click',
        method: 'POST',
        data: {
            link: link,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log('Click tracked successfully');
        },
        error: function () {
            console.log('Error tracking click');
        },
    });
});
$(document).ready(function() {
        $('#cityTable').DataTable({
            "pageLength": 10,
            "lengthChange": true,
            "ordering": true,
            "searching": true,
            "language": {
                "emptyTable": "No records found",
                "searchPlaceholder": "Search cities...",  // 👈 Your placeholder text
                "search": ""  // 👈 This removes the "Search:" label
            },
            "columnDefs": [
                { "orderable": true, "targets": [0, 3] }
            ]
        });
    }); 
// Populate modal with data
$(document).on('click', '.view-btn', function() {
    $('#modalName').text($(this).data('full_name'));
    $('#modalEmail').text($(this).data('email'));
    $('#modalPhone').text($(this).data('phone'));
    $('#modalwhatsapp').text($(this).data('whatsapp'));
    $('#modalgender').text($(this).data('gender'));
    $('#modalage').text($(this).data('age'));
    $('#modalcity').text($(this).data('city'));
    $('#modalinstname').text($(this).data('instagram_name'));
    $('#modalinstlink').text($(this).data('instagram_profile_link'));
    $('#modallinkname').text($(this).data('linkedin_name'));
    $('#modallinklink').text($(this).data('linkedin_profile_link'));
    $('#modalyouname').text($(this).data('youtube_name'));
    $('#modalyoulink').text($(this).data('youtube_profile_link'));
    $('#modaltwittername').text($(this).data('twitter_name'));
    $('#modaltwitterlink').text($(this).data('twitter_profile_link'));
    $('#modalfacename').text($(this).data('facebook_name'));
    $('#modalfacelink').text($(this).data('facebook_profile_link'));
    $('#modalDate').text($(this).data('date'));
});
</script>
@endsection
