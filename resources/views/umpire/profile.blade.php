@extends('umpire.layouts.main')
@section('main-container')
    <style>
        #characterCount {
            color: #09000054;
            margin-top: 9px;
            font-size: 14px;
            font-style: italic;
        }
    </style>
    <div class="body-content">
        <div class="namphomediv">
            <h1 class="pageTitle">Profile </h1>
            <div class="mapbtns-div">
                <div class="d-flex">
                    <div class="chnagepw mr-rit"> <a href="{{ url('umpire/change-password') }}" class="white-btn">Change
                            Password</a></div>
                    <div class="chnagepw"> <a href="javascript:void(0)" onclick="$('form').submit();"
                            class="redbtn">Update</a></div>
                </div>
            </div>
        </div>
        <div class="text-registernumber"> Registered on
            <span class="boldmondtae">{{ date('M d, Y', strtotime($umpire_data->created_at)) }}</span>
        </div>

        <div class="displayes-flexc">
            <div class="formdivs-main">
                <form action="{{ url('umpire/save-profile') }}" class="registfprm" method="POST">
                    @csrf
                    <div class="form-grp">
                        <label for="">Full Name <span class="text-danger">*</span></label>
                        <input readonly type="text" class="textname" placeholder="Enter Name" name="name" required
                            value="{{ $umpire_data->name }}">
                    </div>
                    <div class="displasu">
                        <div class="form-grp groouns ">
                            <label for="">Date of Birth<span class="text-danger"></span></label>
                            <input readonly type="text" class="textname" placeholder="mm/dd/yyyy" name="dob" required
                                value="{{ date('m/d/Y', strtotime($umpire_data->dob)) }}">
                        </div>
                        <div class="form-grp gropus">
                            <label for="">Zip<span class="text-danger"></span></label>
                            <input class="textname" placeholder="Enter Zip" name="zip" required
                                value="{{ $umpire_data->zip }}" type="number" max="5"
                                oninput="if(this.value.length > 5) this.value = this.value.substring(0, 5);">
                        </div>


                    </div>
                    @php
                        if ($umpire_data->profilepic == null) {
                            $src = asset('storage/umpire') . '/img/jone.jpg';
                        } else {
                            $src = asset('storage/images') . '/' . $umpire_data->profilepic;
                        }
                    @endphp
                    <div class="uploadimage">
                        <label for="">Upload Photo</label>
                        <div id="dropzone" class="dropzone">
                            <div class="imagesdiv-forthetsc">
                                <img src="{{ $src }}" class="w-100" alt="">
                            </div>
                            <div class="droptexts"> <span class="text-bules">Click to Upload</span> or <span
                                    class="text-bules">drag & drop</span>
                                <span class="valitatopnsss"><strong>Png,Jpg,Jepg,webp</strong> Upto
                                    <strong>2mb</strong></span>
                            </div>
                        </div>
                        <input type="file" id="fileInput" accept="image/*" style="display:none;" name="file">
                    </div>

                    <div class="form-grp gropus">
                        <label for="">EXperience Bio<span class="text-danger"></span></label>
                        {{-- <textarea name="bio" id="" class="bio-exp">{{ $umpire_data->bio }}</textarea> --}}
                        <textarea name="bio" id="bio" class="bio-exp" maxlength="500" oninput="updateCharacterCount()">{{ $umpire_data->bio }}</textarea>
                        <p id="characterCount">Characters remaining: <span
                                id="count">{{ 500 - strlen($umpire_data->bio) }}</span></p>

                    </div>

                    <div class="macns">
                        <div class="row">
                            <div class="col-md-6"><label for="" class="text-forlabels">scheduling Preferences
                                    :</label>
                                <i class="maisn-i-color">Drag these to rearrange</i>
                            </div>
                            <div class="col-md-6" id="tablecontents" data-url="{{ url('umpire/update-umpire-pref') }}">
                                @if ($prefs->count() > 0)
                                    @foreach ($prefs as $pref)
                                        <section class="toogglee-upssdiv active-top row1" data-id="{{ $pref->id }}">
                                            <div class="eqal-sign">=</div>
                                            <div class="toggle-updiv">
                                                {{ $pref->leagueid == 0 ? 'Highest Paid Games' : $pref->league->leaguename }}
                                            </div>
                                        </section>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            <div class="whitefields">
                <div class="title-with-btn">
                    <h2 class="white-lists">whitelisted fields</h2>
                    <div class="btn-white">
                        <a class="rounded-btn">Make Blacklist</a>
                    </div>
                </div>

                <div class="btns-siderows">
                    @if ($locations->count() > 0)
                        @foreach ($locations as $location)
                            @php
                                $class = '';
                                if (in_array($location->locid, $blockedGround_ids)) {
                                    $class = 'toggled-class';
                                }
                            @endphp
                            <div class="buton-div-tiggler-class">
                                <button onclick="blockUnblockGround(this);" class="tiggle-btns redbtn {{ $class }}"
                                    type="button"
                                    data-location_id="{{ $location->locid }}">{{ $location->ground }}</button>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function() {
            jQuery('#datepeaker-Dob').datepicker({
                format: 'dd-mm-yyyy',
                startDate: '+1d'
            });
        });
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const droptexts = document.querySelector('.droptexts');
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('hover');
        });
        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('hover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('hover');

            const files = e.dataTransfer.files;

            if (files.length > 0) {
                handleFiles(files);
            }
        });

        dropzone.addEventListener('click', () => {
            fileInput.click();
        });
        fileInput.addEventListener('change', (e) => {
            const files = e.target.files;

            if (files.length > 0) {
                handleFiles(files);
            }
        });

        function handleFiles(files) {
            for (const file of files) {
                if (file.type.startsWith('image/')) {
                    const imgURL = URL.createObjectURL(file);
                    const newImgTag = document.createElement('img');
                    newImgTag.src = imgURL;
                    const existingImgTag = document.querySelector('.dropzone img');
                    if (existingImgTag) {
                        existingImgTag.parentNode.replaceChild(newImgTag, existingImgTag);
                    } else {
                        dropzone.appendChild(newImgTag);
                    }
                    droptexts.innerHTML = `Selected File: <strong>${file.name}</strong>`;
                }
            }
        }
    </script>
    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 1) {
                            toastr.success('Success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function(response) {
                        // toastr.error('Something went wrong..!!');
                    }
                });
            }));
        });
    </script>
    <script>
        function blockUnblockGround(element) {
            if ($(element).hasClass('toggled-class')) {
                $(element).removeClass('toggled-class');
            } else {
                $(element).addClass('toggled-class');
            }
            let location_id = $(element).data('location_id');
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "{{ url('umpire/block-unblock-ground') }}",
                data: {
                    location_id: location_id,
                    _token: token
                },
                dataType: "json",
                success: function(res) {
                    if (res.status == 1) {
                        toastr.success('Success');
                    } else {
                        toastr.error('Something went wrong..!!');
                    }
                },
                error: function(response) {
                    toastr.error('Something went wrong..!!');
                }
            });
        }
    </script>
    <script>
        function updateCharacterCount() {
            var bioTextarea = $('#bio');
            var countSpan = $('#count');
            var maxLength = parseInt(bioTextarea.attr('maxlength'));
            var currentLength = bioTextarea.val().length;
            countSpan.text(maxLength - currentLength);

            if (currentLength > maxLength) {
                bioTextarea.val(bioTextarea.val().slice(0, maxLength));
            }
        }
        $(document).ready(function() {
            updateCharacterCount();
        });
    </script>

@endsection
