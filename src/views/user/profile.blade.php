@extends('admin::layouts.user')

@section('container')
<div class="container" ng-controller="controllerProfile">
    <div class="row padded">
        @if( isset($_status) )
        <div class="alert alert-{{ $status['type'] }}">
            <button class="close" data-dismiss="alert" type="button">x</button>
            {{ $_status['message'] }}
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
            <div class="box">
                <div class="box-header">
                    <ul id="user-tabs" class="nav nav-tabs nav-tabs-left">
                        <li class="active"><a href="#tab-account" data-toggle="tab">{{ trans('admin::user.title_account') }}</a></li>
                        <!--<li><a href="#tab-permission" data-toggle="tab">{{ trans('admin::user.title_access') }}</a></li>-->
                    </ul>
                </div>
                <div class="box-content">
                    <div class="tab-content">
                        <div id="tab-account" class="tab-pane padded active">
                            <div class="form-flat">
                                <div class="input-group">
                                    {{ Former::open()->name('form_account') }}
                                        {{ Former::label('name')->class('control-label col-lg-2') }}
                                        <div class="col-lg-5">{{ Former::text('first_name')->class('validate[required]')->ng_model('user.first_name') }}</div>
                                        <div class="col-lg-5">{{ Former::text('last_name')->class('validate[required]')->ng_model('user.last_name') }}</div>
                                    {{ Former::close() }}
                                </div>
                                <div class="input-group">
                                    {{ Former::label('email')->class('control-label col-lg-2') }}
                                    <div class="col-lg-5">
                                        <a href="#" editable-text="user.email" onbeforesave="validateEmail($data)">[[ user.email || "{{ $user['email'] }}"]]</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-permission" class="tab-pane padded">
                            <div class="form-flat">
                                <div class="input-group">
                                    {{ Former::label('role')->class('control-label col-lg-2') }}
                                    <div class="col-lg-10">
                                        {{ Former::select('role')->class('uniform')->fromQuery( Code::byCategory('location.state'), 'value', 'name' ) }}
                                    </div>
                                </div>
                                <div class="input-group">
                                    {{ Former::label('group')->class('control-label col-lg-2') }}
                                    <div class="col-lg-10">
                                        {{ Former::select('group')->class('select2')->multiple('multiple')->fromQuery( Code::byCategory('location.state'), 'value', 'name' ) }}
                                    </div>
                                </div>
                                <div class="input-group">
                                    {{ Former::label('permission')->class('control-label col-lg-2') }}
                                    <div class="col-lg-10">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{ Former::open()->name('form_profile')->class('form-horizontal fill-up validatable')->ng_submit('processForm()') }}
                <div class="box">
                    <div class="box-header">
                        <span class="title">{{ trans('admin::user.title_profile') }}</span>
                    </div>
                    <div class="box-content">
                        <div class="padded">
                            <div class="form-group">
                                {{ Former::label('idno_ic')->class('control-label col-lg-2') }}
                                <div class="col-lg-10">{{ Former::text('profile.idno_ic')->class('validate[required,custom[idICNo]]')->ng_model('user.profile.idno_ic') }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('race')->class('control-label col-lg-2') }}
                                <div class="col-lg-6">
                                    {{ Former::select('profile.race')->class('uniform')->fromQuery( Code::byParentName('native') )->ng_model('user.profile.race') }}
                                </div>
                                <div class="col-lg-2">
                                    <input type="radio" name="profile.gender" class="validate[required]" value="male" ng-model="user.profile.gender" as-ui-icheck>
                                    {{ Former::label('gender_male') }}
                                </div>
                                <div class="col-lg-2">
                                    <input type="radio" name="profile.gender" class="validate[required]" value="female" ng-model="user.profile.gender" as-ui-icheck>
                                    {{ Former::label('gender_female') }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('birth')->class('control-label col-lg-2') }}
                                <div class="col-lg-3">
                                    {{ Former::text('profile.birth_date')->class('datepicker validate[required]')->ng_model('user.profile.birth_date') }}
                                </div>
                                <div class="col-lg-7">
                                    {{ Former::text('profile.birth_place')->class('validate[required]')->placeholder('admin::user.label_birth_place')->ng_model('user.profile.birth_place') }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('phone')->class('control-label col-lg-2') }}
                                <div class="col-lg-5">{{ Former::text('profile.contact_home')->class('validate[required,custom[number]]')->placeholder('admin::user.label_contact_home')->ng_model('user.profile.contact_home') }}</div>
                                <div class="col-lg-5">{{ Former::text('profile.contact_mobile')->class('validate[required,custom[number]]')->placeholder('admin::user.label_contact_mobile')->ng_model('user.profile.contact_mobile') }}</div>
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
                                <div class="col-lg-10">{{ Former::text('profile.address_street')->class('validate[required]')->ng_model('user.profile.address_street') }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('address_area')->class('control-label col-lg-2') }}
                                <div class="col-lg-8">{{ Former::text('profile.address_area')->class('validate[required]')->ng_model('user.profile.address_area')->placeholder('admin::user.label_address_area') }}</div>
                                <div class="col-lg-2">{{ Former::text('profile.address_postcode')->class('validate[required,custom[number]]')->ng_model('user.profile.address_postcode')->placeholder('admin::user.label_address_postcode') }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('address_citystate')->class('control-label col-lg-2') }}
                                <div class="col-lg-5">{{ Former::select('address_city')->class('uniform')->fromQuery( Code::byCategory('location.state'), 'value', 'name' )->ng_model('user.profile.address_city') }}</div>
                                <div class="col-lg-5">{{ Former::select('address_state')->class('uniform')->fromQuery( Code::byCategory('location.state'), 'value', 'name' )->ng_model('user.profile.address_state') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="well clearfix">
                    <div class="pull-right">
                        <button id="btnUpdateProfile" type="submit" class="btn btn-green">{{ trans('admin::user.btn_update_profile') }}</button>
                    </div>
                </div>
            {{ Former::close() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    @parent
    <script language="JavaScript" type="text/javascript">
        var urlAPIUser = appBase + 'api/v1/users/';

        $(document).ready(function(){
            $('#profile\\.birth_date').datepicker("option","changeYear",true);
            $('#profile\\.birth_date').datepicker("option","maxDate",'-15Y');
        });

        function controllerProfile($scope,$http,$q,Api){
            var params = {model:'users', id:'{{ $user->id }}'};

            //[i] Getting user model
            $scope.user = Api.get(params);

            //[i] Updating data user model
            $scope.processForm = function(){
                if( $('#form_profile').validationEngine('validate') ){
                    $scope.user.$update(params);
                }
            }

            //[i] Changing email
            $scope.validateEmail = function(data){
                var d = $q.defer();

                $http.post(urlAPIUser + 'change-email', {email: data}).success(function(res){
                    res = res || {};
                    if( res.status === 'success' ){
                        d.resolve();
                    }else{
                        d.resolve(res.message);
                    }
                }).error(function(e){
                    d.reject('Server error!');
                });

                return d.promise;
            }
        }

    </script>
@stop