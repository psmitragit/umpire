@extends('league.layouts.main')
@section('main-container')
    <div class="body-content ">
        <div class="namphomediv">
            <h1 class="pageTitle">League Umpires</h1>
            <div class="mapbtns-div d-flex">
                <div class="red-bnts-tras">
                    <a href="{{ url('league/view-new-applicants') }}" class="matsbn">View
                        {{ league_new_applicant_count($league_data->leagueid) }} New Applicants</a>
                </div>
                <div class="Admins">
                    <div class="inputs-srch">
                        <input oninput="filterTable('myTable2');" id="searchInput" placeholder="Search"
                            class="input-srch-field" type="text">
                        <button class="srch-mag-btn" type="button"> <img
                                src="{{ asset('storage/league') }}/img/srch-icon.png" alt=""> </button>

                    </div>

                </div>
            </div>
        </div>



        <div class="list-viw-contet w-75 mx-auto customhight-100vh" id="list-conssst2sc">


            <div >
              <h3 class="title-texts">Click on an umpires name to view their profile</h3>
                <div class="row dvs">
                    @if ($page_data->count() > 0)
                        @foreach ($page_data as $data)
                            <div class="col-lg-3 col-md-4 col-6">
                                <a href="{{ url('league/manage-umpire/' . $data->umpire->umpid) }}"
                                    class="blueviewbtn sd">{{ $data->umpire->name }}</a>
                            </div>
                        @endforeach
                    @else
                        <div>No umpire found</div>
                    @endif


                </div>

            </div>

            </div>


        </div>
    </div>
    <script>
        $(document).ready(function() {
            filterTable('myTable2');
        });
    </script>
@endsection
