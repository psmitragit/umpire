@if ((int) getMetaValue('SHOW_FEEDBACK_OPTION') == 1)
    <div class="fix-buttons">
        <button style="position:relative;z-index: 999" type="button" class="bate-font" data-bs-toggle="modal"
            data-bs-target="#feedbackModal" title="Feedback">
            <i class="fa-solid fa-comment-dots"></i>
        </button>

    </div>
    <!-- Feedback Body -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="">
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
                        <div class="modal-hesn">
                            <h5 class="modalicons-title"> Send Feedback</h5>
                            <button type="button" class="btn-closes" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="fa-solid fa-x"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="modalqstnbalbe">
                                <label for="" class="form-label">Name</label>
                                <input {{ $feedbackSenderName ? 'value=' . $feedbackSenderName . ' readonly' : '' }}
                                    type="text" class="form-control" name="feedbackSenderName" id=""
                                    aria-describedby="helpId" placeholder="" / required>
                            </div>
                            <div class="modalqstnbalbe">
                                <label for="" class="form-label">Email</label>
                                <input {{ $feedbackSenderEmail ? 'value=' . $feedbackSenderEmail . ' readonly' : '' }}
                                    type="email" class="" name="feedbackSenderEmail" id=""
                                    aria-describedby="helpId" placeholder="" / required>
                            </div>
                            <div class="modalqstnbalbe">
                                <label for="">Message *</label>
                                <textarea class="" name="feedback_message" rows="3"></textarea>
                            </div>
                            <div class="text-center submit-bten-modal">

                                <button type="submit" class="submitbtns">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
