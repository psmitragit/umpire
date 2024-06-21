@extends('admin.layouts.main')
@section('main-container')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <div class="content-wrapper">
        <div class="alert alert-danger" style="display: none;" id="errors"></div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>
                        <hr>
                        <form class="forms-sample customwiths" method="post" action="{{ url('admin/subscription') }}">
                            @csrf
                            <div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tire One Title<span class="text-danger">
                                            *</span></label>
                                    <input type="text" name="title_1" class="form-control custom-input"
                                        value="{{ getCMSContent('subscription', 'title_1') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> Tire One Sub Title<span class="text-danger">
                                        </span></label>
                                    <input type="text" name="sub_title_1" class="form-control custom-input"
                                        value="{{ getCMSContent('subscription', 'sub_title_1') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tire One Content<span class="text-danger">
                                            *</span></label>
                                    <div class="editor">{!! getCMSContent('subscription', 'editor_1') !!}</div>
                                    <input type="hidden" name="editor_1" id="editor_1">
                                </div>
                            </div>
                            <hr>
                            <div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tire Two Title<span class="text-danger">
                                            *</span></label>
                                    <input type="text" name="title_2" class="form-control custom-input"
                                        value="{{ getCMSContent('subscription', 'title_2') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> Tire Two Sub Title<span class="text-danger">
                                        </span></label>
                                    <input type="text" name="sub_title_2" class="form-control custom-input"
                                        value="{{ getCMSContent('subscription', 'sub_title_2') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tire Two Content<span class="text-danger">
                                            *</span></label>
                                    <div class="editor">{!! getCMSContent('subscription', 'editor_2') !!}</div>
                                    <input type="hidden" name="editor_2" id="editor_2">
                                </div>
                            </div>
                            <hr>
                            <div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tire Three Title<span class="text-danger">
                                            *</span></label>
                                    <input type="text" name="title_3" class="form-control custom-input"
                                        value="{{ getCMSContent('subscription', 'title_3') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> Tire Three Sub Title<span class="text-danger">
                                        </span></label>
                                    <input type="text" name="sub_title_3" class="form-control custom-input"
                                        value="{{ getCMSContent('subscription', 'sub_title_3') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tire Three Content<span class="text-danger">
                                            *</span></label>
                                    <div class="editor">{!! getCMSContent('subscription', 'editor_3') !!}</div>
                                    <input type="hidden" name="editor_3" id="editor_3">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const editors = [];

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.editor').forEach((editorElement, index) => {
                ClassicEditor
                    .create(editorElement, {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'link', '|',
                                'bulletedList', 'numberedList', '|',
                                'undo', 'redo'
                            ]
                        },
                        removePlugins: [
                            'ImageUpload', 'MediaEmbed', 'EasyImage', 'Image', 'ImageToolbar',
                            'ImageCaption', 'ImageStyle', 'ImageResize', 'ImageInsert', 'CKFinder'
                        ]
                    })
                    .then(editor => {
                        editors[index] = editor;
                        console.log(`Editor ${index + 1} initialized`);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });



        function getContents() {
            editors.forEach((editor, index) => {
                const content = editor.getData();
                const hiddenInput = document.querySelector(`#editor_${index + 1}`);
                hiddenInput.value = content;
                console.log(`Content of Editor ${index + 1}:`, content);
            });
        }
    </script>

    <script>
        $(document).ready(function(e) {
            $('form').on('submit', (function(e) {
                e.preventDefault();
                getContents();
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
                        toastr.success("Success");
                    },
                    error: function(response) {

                        if (response.status === 422) {
                            var errorString = '';
                            var errors = response.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    var errorMessage = errors[key][0];
                                    errorString += '<p>' + errorMessage + '</p>';
                                }
                                $('html, body').animate({
                                    scrollTop: 0
                                });
                                $('#errors').show().delay(5000).hide(0);
                                $('#errors').html(errorString);
                            }
                        }
                    }
                });
            }));
        });
    </script>
@endsection
