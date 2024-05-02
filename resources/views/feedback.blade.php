@if ((int) getMetaValue('SHOW_FEEDBACK_OPTION') == 1)
    <butto style="position:relative;z-index: 999" type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
        data-bs-target="#feedbackModal">
        Send feedback
    </butto>
    <!-- Feedback Body -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form action="{{ url('send-feedback') }}" method="POST">
                    @csrf
                    @php
                        $feedbackSenderName = null;
                        $feedbackSenderEmail = null;
                        if ($user = logged_in_league_admin_data()) {
                            $feedbackSenderName = $user->league->name;
                            $feedbackSenderEmail = $user->email;
                        } elseif ($user = logged_in_umpire_data()) {
                            $feedbackSenderName = $user->name;
                            $feedbackSenderEmail = $user->user->email;
                        }
                    @endphp
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Send Feedback
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Name</label>
                            <input {{ $feedbackSenderName ? 'value=' . $feedbackSenderName . ' readonly' : '' }}
                                type="text" class="form-control" name="feedbackSenderName" id=""
                                aria-describedby="helpId" placeholder="" / required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input {{ $feedbackSenderEmail ? 'value=' . $feedbackSenderEmail . ' readonly' : '' }}
                                type="email" class="form-control" name="feedbackSenderEmail" id=""
                                aria-describedby="helpId" placeholder="" / required>
                        </div>
                        <div class="form-group">
                            <label for="">Message *</label>
                            <textarea class="form-control" name="feedback_message" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
