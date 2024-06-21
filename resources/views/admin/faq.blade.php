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
                        <form class="forms-sample customwiths" method="post"
                            action="{{ url('admin/faq') }}{{ !empty($_GET['section']) ? '?section='.$_GET['section'] : '' }}">
                            @csrf
                            <div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Question<span class="text-danger">
                                            *</span></label>
                                    <input type="text" name="question" class="form-control custom-input"
                                        value="{{ getFAQ(@$_GET['section'], 'question') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Answer<span class="text-danger">
                                            *</span></label>
                                    <div class="editor">{!! getFAQ(@$_GET['section'], 'answer') !!}</div>
                                    <input type="hidden" name="answer" id="editor_1">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                        <div class="table-responsive">
                            <table id="faqTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Action</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                    </tr>
                                </thead>
                                <tbody id="tablecontents">
                                    @if ($page_data->count() > 0)
                                        @foreach ($page_data as $data)
                                            @php
                                                $value = json_decode($data->value, true);
                                                $qstn = $value['question'];
                                                $ans = $value['answer'];
                                            @endphp
                                            <tr class="row1" data-id="{{ $data->id }}">
                                                <td>
                                                    <div style="color:rgb(158, 156, 156); padding-left: 10px; float: left; font-size: 20px; cursor: move;"
                                                        title="change display order"><i
                                                            class="fa-solid fa-arrows-up-down-left-right"></i></div>
                                                </td>
                                                <td>
                                                    <a href="{{ url('admin/faq?section=' . $data->section) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <a href="{{ url('admin/delete-faq/' . $data->id) }}"
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                                                </td>
                                                <td>{{ $qstn }}</td>
                                                <td class="text-sgehh">
                                                <div class="text-asb">    
                                                    {!! $ans !!}
                                                
                                                </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td align="center" colspan="9">No faq Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
                        $('form')[0].reset();
                        window.location.reload();
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
    <script type="text/javascript">
        $(function() {
            $("#tablecontents").sortable({
                items: "tr",
                cursor: 'move',
                opacity: 0.6,
                update: function() {
                    sendOrderToServer();
                }
            });
            $(document).ready(function() {
                sendOrderToServer();
            });

            function sendOrderToServer() {
                var order_url = "{{ url('admin/order-faq') }}";
                var order = [];
                var token = $('meta[name="csrf-token"]').attr('content');
                $('tr.row1').each(function(index, element) {
                    order.push({
                        id: $(this).attr('data-id'),
                        position: index + 1
                    });
                });
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: order_url,
                    data: {
                        order: order,
                        _token: token
                    },
                    success: function(response) {}
                });
            }
        });
    </script>
@endsection
