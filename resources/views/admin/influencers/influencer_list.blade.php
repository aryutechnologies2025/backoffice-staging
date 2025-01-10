<!-- resources/views/admin/influencers/influencer_list.blade.php -->

@extends('layouts.app')

@section('content')
<style>
    a:hover {
        color: red;
    }
    a {
        color: rgb(37, 150, 190);
    }
    .city {
        color: blue;
    }
    .custom-message-modal {
        width: 100%!important;
    }
</style>

<div class="row body-sec py-5 px-5 justify-content-around">
    <div class="col-lg-6">
        <b><a href="/dashboard">Dashboard</a> > <a class="city" href="/address">Address</a></b>
        <br><br>
        <h3 class="fw-bold">{{ $title }}</h3>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.influencer_add_form') }}">
                <button class="btn btn-add px-5" type="button">Add Influencer</button>
            </a>
        </div>
    </div>
</div>

<!-- EVENT LIST -->
<div class="row body-sec px-8">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 mb-5">
            <table id="cityTable" class="table pt-2">
                <thead>
                    <tr class="rounded-top-4">
                        <th class="text-center"><span>S.No</span></th>
                        <th class="text-center"><span>Name</span></th>
                        <th class="text-center"><span>Reference_id</span></th>
                        <th class="text-center"><span>Email</span></th>
                        <th class="text-center"><span>Date&Time</span></th>
                        <th class="text-center"><span>Referral code</span></th>
                        <th class="text-center"><span>Affiliate Link</span></th>
                        <th class="text-center"><span>Action</span></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($influencers as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $row->full_name }}</td>
                        <td class="text-center">{{ $row->reference_id }}</td>
                        <td class="text-center">{{ $row->email }}</td>
                        <td class="text-center">{{ $row->created_at }}</td>
                        <td class="text-center" style="font-size: small;">{{ $row->referral_code }}</td>
                        <td class="text-center" style="width: 10%;">
                            <button class="btn btn-sm btn-info view-links" data-id="{{ $row->id }}" data-name="{{ $row->full_name }}"
                            data-signup-url="{{ url('/signup/' . $row->reference_id) }}">
                                View Affiliate Links
                            </button>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.influencer_edit_form', $row->id) }}" class="table-edit-link">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                            <a href="javascript:void(0);" class="table-link danger delconfirm" data-row_id="{{ $row->id }}" data-act_url="{{ route('admin.influencer_delete') }}" data-csrf_token="{{ csrf_token() }}">
                                <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
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

<!-- Affiliate Links Modal -->
<div class="custom-message-modal modal fade" id="affiliateLinksModal" tabindex="-1" aria-labelledby="affiliateLinksLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
    $('#affiliateLinksLabel').html(`Affiliate Links for ${influencerName} <a href="${signupUrl}" target="_blank">${signupUrl}</a>`);
    // Display signup URL in the modal
    $('#links-container').html(`
        <p><strong>Signup URL:</strong> <a href="${signupUrl}" target="_blank">${signupUrl}</a></p>
        <p>Loading affiliate links...</p>
    `);
    $.ajax({
        url: `/admin/influencer/${influencerId}/affiliate-links`,
        method: 'GET',
        success: function (response) {
            if (response.status === '1') {
                const links = response.data;
                const linksHtml = links
                    .map(link => `<p><strong>${link.title}:</strong> <a href="${link.link}" target="_blank">${link.link}</a></p>`)
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
</script>
@endsection
