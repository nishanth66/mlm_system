<?php
$listId = 0;
?>
@include('samybot.samybot_topbar')
<div class="container-fluid affiliate-container">
    @include('flash::message')
    @include('adminlte-templates::common.errors')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-2  col-sm-12 col-xs-12">
                <input type="text" class="form-control" id="search_text" placeholder="{{trans('home.search')}}">
                <button class="samy_campians_btn" type="button" onclick="searchUser()">{{trans('home.search')}}</button>
                <br><br>
                <a href="{{url('samybot/prospects/csv')}}">
                    <button class="btn btn-primaryy" style="width:100%;" type="button" onclick="searchUser()">{{trans('home.export_csv')}}</button>
                </a>
            </div>
            <br>
            <div class="col-md-10 col-xs-12 col-xs-12 samy_sidebar" id="prospectsDiv">
                @if(isset($prospects))
                    @foreach($prospects as $prospect)
                        <?php
                            $user = \App\User::whereId($prospect->user_id)->first();
                            $Mailchimp = DB::table('company_mailchimp_list')->where('company_id',$prospect->company_id)->where('name','prospects')->first();
                            if(!empty($Mailchimp)){
                                $listId = $Mailchimp->list_id;
                            }else{
                                $listId = 0;
                            }
                        ?>
                        @if(!empty($user))
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="col-md-12 samy_border affiliate-container">
                                <div class="col-md-4">
                                    @if(isset($user->photo))
                                        <img class="prospect_img img-circle" src="{{asset('public/avatars').'/'.$user->photo}}">
                                    @else
                                        <img class="prospect_img img-circle" src="{{asset('public/pictures/default.jpg')}}">
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h4>{{$user->name}}</h4>
                                    {{--@if($prospect->IsSynced == 1)--}}
                                        {{--<button class="pull-right samy_btn" style="width: auto" disabled>Synced</button>--}}
                                    {{--@else--}}
                                        {{--<button class="pull-right samy_btn" style="width: auto" onclick="SyncMail('{{$prospect->user_id}}','{{$prospect->company_id}}')">Sync</button>--}}
                                    {{--@endif--}}
                                    <span class="affiliate-other-details">{{$user->email}}</span> <br/>
                                    <span class="affiliate-other-details">{{date('d/m/Y',strtotime($user->created_at))}}</span> <br/>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <center>
                        <img src="{{asset('public/pictures/not found.svg')}}" class="section1_logo"> <h4>{{trans('samybot/common.no_data')}}</h4>
                    </center>
                @endif
            </div>
        </div>
    </div>
</div>
@include('frontEnd.footer')
<script>
    $(".prospect_img").each(function() {
        $(this).on("error", function () {
            $(this).unbind("error").attr("src", "{{asset('public/campaign_images/default.png')}}");
        });
    });
    function searchUser() {
        var val = $('#search_text').val();
        $.ajax({
            url: "{{url('samybot/searchUser')}}" + '/' + val,
            success: function (results) {
                if (results != '') {
                    $('#prospectsDiv').html(results);
                }
                else {
                    var html = "<center><img src='{{asset("public/pictures/not found.svg")}}'>' "+
                               "<h4>{{trans('samybot/common.no_data')}}</h4></center>";
                    $('#prospectsDiv').html(html);
                }
            }
        });
    }

    function SyncMail(userId,company_id) {
        $.ajax({
            url: "{{url('samybot/syncMailchimp')}}" + '/' + userId + '/'  + company_id + '/' + '{{$listId}}' + '/' + 'prospect',
            success: function (results) {
                window.location.reload();
            }
        });
    }
</script>