@extends('admin::layouts.user')

@section('container')
<div class="container">
    <div class="row padded">
        @if( isset($status) )
        <div class="alert alert-{{ $status['type'] }}">
            {{ $status['message'] }}
        </div>
        @endif
    </div>

    <div class="row">
            <div class="col-md-3">
                <div class="thumbnail" style="width: 200px; height: 200px;">
                    <img class="media-object" src="{{ Gravatar::src($user->email,200) }}" width="200" height="200" alt="avatar" />
                </div>
            </div>
            <div class="col-md-9">
            <form id="form_profile" class="form-horizontal fill-up validatable" action="?action={{ $form_action }}">

                <div class="box">
                    <div class="box-header">
                        <ul id="user-tabs" class="nav nav-tabs nav-tabs-left">
                            <li class="active"><a href="#tab-account" data-toggle="tab">{{ trans('admin::user.title_account') }}</a></li>
                            <li><a href="#tab-profile" data-toggle="tab">{{ trans('admin::user.title_profile') }}</a></li>
                        </ul>
                    </div>

                    <div class="box-content">
                        <div class="tab-content">
                            <div id="tab-account" class="tab-pane padded active">
                                <div class="form-group">
                                    {{ Former::label('name')->class('control-label col-lg-2') }}
                                    <div class="col-lg-5">{{ Former::text('first_name')->class('validate[required]') }}</div>
                                    <div class="col-lg-5">{{ Former::text('last_name')->class('validate[required]') }}</div>
                                </div>
                                <div class="form-group">
                                    {{ Former::label('email')->class('control-label col-lg-2') }}
                                    <div class="col-lg-5">{{ Former::text('email')->class('validate[required,custom[email]]')->placeholder('Email') }}</div>
                                </div>
                            </div>
                            <div id="tab-profile" class="tab-pane padded"></div>
                        </div>
                        <div class="box-footer padded">
                            <btn id="btnUpdate" href="#" class="btn btn-green">{{ trans('admin::user.btn_update') }}</btn>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header">
                        <span class="title">{{ trans('admin::user.title_profile') }}</span>
                    </div>
                    <div class="box-content">
                        <div class="padded">
                            <div class="form-group">
                                {{ Former::label('idno_ic')->class('control-label col-lg-2') }}
                                <div class="col-lg-10">{{ Former::text('profile.idno_ic')->class('validate[required,custom[idICNo]]') }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('race')->class('control-label col-lg-2') }}
                                <div class="col-lg-6">
                                    {{ Former::select('profile.race')->class('uniform')->fromQuery( Code::byParentName('native') ) }}
                                </div>
                                <div class="col-lg-2">
                                    {{ Former::radio('profile.gender')->class('icheck validate[required]')->check() }}
                                    {{ Former::label('gender_male') }}
                                </div>
                                <div class="col-lg-2">
                                    {{ Former::radio('profile.gender')->class('icheck validate[required]') }}
                                    {{ Former::label('gender_female') }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('birth')->class('control-label col-lg-2') }}
                                <div class="col-lg-3">
                                    {{ Former::text('profile.birth_date')->class('datepicker validate[required,custom[dateFormatdMMy]]')->placeholder('admin::user.birth_date') }}
                                </div>
                                <div class="col-lg-7">
                                    {{ Former::text('profile.birth_place')->class('validate[required]')->placeholder('admin::user.label_birth_place') }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('phone')->class('control-label col-lg-2') }}
                                <div class="col-lg-5">{{ Former::text('profile.contact_home')->class('validate[required,custom[number]]')->placeholder('admin::user.label_contact_home') }}</div>
                                <div class="col-lg-5">{{ Former::text('profile.contact_mobile')->class('validate[required,custom[number]]')->placeholder('admin::user.label_contact_mobile') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header">
                        <span class="title">{{ trans('admin::user.title_address') }}</span>
                    </div>
                    <div class="box-content">
                        <div class="padded">
                            <div class="form-group">
                                {{ Former::label('address_street')->class('control-label col-lg-2') }}
                                <div class="col-lg-10">{{ Former::text('profile.address_street')->class('validate[required]') }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('address_area')->class('control-label col-lg-2') }}
                                <div class="col-lg-8">{{ Former::text('profile.address_area')->class('validate[required]')->placeholder('admin::user.label_address_area') }}</div>
                                <div class="col-lg-2">{{ Former::text('profile.contact_postcode')->class('validate[required,custom[number]]')->placeholder('admin::user.label_address_postcode') }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('address_citystate')->class('control-label col-lg-2') }}
                                <div class="col-lg-5">{{ Former::select('address_city')->class('uniform')->fromQuery( Code::byCategory('location.state'), 'value', 'name' ) }}</div>
                                <div class="col-lg-5">{{ Former::select('address_state')->class('uniform')->fromQuery( Code::byCategory('location.state'), 'value', 'name' ) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            </div>
    </div>
</div>
@stop

@section('javascript')
    @parent
    <script language="JavaScript" type="text/javascript">
        var form_action = '{{ $form_action }}',
            urlAPIUser = appBase + 'api/v1/user',
            urlAPIZone = appBase + 'api/v1/zone';

        /*_a.ready(function() {
            $('#user-tabs').tabs();
            $("#btnUpdate").on('click',FormSubmit);
            $("#btnUpdate2").on('click',FormSubmit);
            $("#btnEditPassword").on('click',formEditPassword);

            $("#state_id").bind('change',function(){
                branchID = $('#state_id').val() != '{{-- state_id --}}' ? '' : '{{-- branch_id --}}';
                data = 'format=htmloption&category=branch&state_id=' + $('#state_id').val() + '&branch_id=' + branchID;

                selectLoadOptions("#branch_id", urlAPIZone, data, '', function(){
                    $("#branch_id").trigger("liszt:updated");
                });
            });

            $("input.datepicker").datepicker({
                autoSize : true,
                appendText : '(dd-mm-yyyy)',
                dateFormat : 'dd-mm-yy'
            });

            $("#state_id").trigger('change');
        });*/


        function FormSubmit(e){
            if ( e.type == 'keypress' && e.keyCode != 13 ) return;
            _a.alert.clear();

            var valid = $('#form_profile').validationEngine('validate');
            if(valid == false) return false;

            data = $('#form_profile').serialize();
            if( form_action == 'edit' ){
                data = data + '&user_id={{ $user->id }}';
            }

            $.put(urlAPIUser + '?' + data, data, function(response) {
                _a.alert.server(response);
                if (response.status != 1) {
                    _a.validation.alert(response.errors);
                } else {
                    formEditPasswordClear();
                }
            })
        }

    </script>
@stop