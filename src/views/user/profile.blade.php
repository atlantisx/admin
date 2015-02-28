@extends('themes/default::layouts.fluid')

@section('container')
<div class="container" ng-controller="controllerProfile" style="margin-top: 15px;">
    @include('core::partials.error')

    <div class="col-fixed">
        <div class="col-fixed-200">
            <div class="thumbnail" style="width: 200px; height: 200px;">
                <a href="http://www.gravatar.com/{{ md5($user_profile->email) }}.hcard" target="_blank"><img class="media-object" src="{{ Gravatar::src($user_profile->email,200) }}" width="200" height="200" alt="avatar" /></a>
            </div>
        </div>
        <div class="content">
            <div class="box">
                <div class="box-header">
                    <ul id="user-tabs" class="nav nav-tabs nav-tabs-left">
                        <li class="active"><a href="#tab-account" data-toggle="tab">{{ trans('admin::user.title_account') }}</a></li>
                        <li><a href="#tab-permission" data-toggle="tab">{{ trans('admin::user.title_access') }}</a></li>
                    </ul>
                </div>
                <div class="box-content">
                    <div class="tab-content">
                        <div id="tab-account" class="tab-pane padded active">
                            <div class="form-flat">
                                <div class="input-group">
                                    {{ Former::open()->name('form_account') }}
                                        {{ Former::label('name')->class('control-label col-lg-2') }}
                                        <div class="col-lg-5">{{ Former::text('first_name')->class('validate[required]')->as_ui_validation()->ng_model('user.first_name') }}</div>
                                        <div class="col-lg-5">{{ Former::text('last_name')->class('validate[required]')->as_ui_validation()->ng_model('user.last_name') }}</div>
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
                            {{ Former::open()->name('form_access') }}
                                <div class="input-group">
                                    {{ Former::label('role')->class('control-label col-lg-2') }}
                                    <div class="col-lg-10">
                                        {{ Former::select('role')
                                            ->fromQuery( Role::all(), 'name', 'id' )
                                            ->ng_disabled( !$user->inGroup(Sentry::findGroupByName('Admin')) )
                                            ->ng_model('user._roles')
                                            ->ui_select2()
                                            ->multiple() }}
                                    </div>
                                </div>
                                <div class="input-group">
                                    {{ Former::label('group')->class('control-label col-lg-2') }}
                                    <div class="col-lg-10">
                                        {{ Former::select('group')
                                            ->fromQuery( Group::all(), 'name', 'id' )
                                            ->ng_disabled( !$user->inGroup(Sentry::findGroupByName('Admin')) )
                                            ->ng_model('user._groups')
                                            ->ui_select2()
                                            ->multiple() }}
                                    </div>
                                </div>
                                <div class="input-group hidden">
                                    {{ Former::label('permission')->class('control-label col-lg-2') }}
                                </div>
                            {{ Former::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{ Former::open()->name('form_profile')->class('form-horizontal fill-up') }}
                <div class="box">
                    <div class="box-header">
                        <span class="title">{{ trans('admin::user.title_profile') }}</span>
                    </div>
                    <div class="box-content">
                        <div class="padded">
                            <div class="form-group">
                                {{ Former::label('idno_ic')->class('control-label col-lg-2') }}
                                <div class="col-lg-10">{{ Former::text('profile.idno_ic')->class('validate[required,custom[idICNo]]')->as_ui_validation()->ng_model('user.profile.idno_ic') }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('race')->class('control-label col-lg-2') }}
                                <div class="col-lg-6">
                                    {{ Former::select('profile.race')
                                        ->fromQuery( Code::parentName('native'), 'value', 'name' )
                                        ->ng_model('user.profile.race')
                                        ->ui_select2() }}
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
                                    {{ Former::text('profile.birth_date')->class('validate[required,custom[date],past[now]]')->as_ui_datepicker('dd-mm-yy')->as_ui_validation()->ng_model('user.profile.birth_date') }}
                                </div>
                                <div class="col-lg-7">
                                    {{ Former::text('profile.birth_place')->class('validate[required]')->as_ui_validation()->placeholder(trans('admin::user.label_birth_place'))->ng_model('user.profile.birth_place') }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('phone')->class('control-label col-lg-2') }}
                                <div class="col-lg-5">{{ Former::text('profile.contact_home')->class('validate[required,custom[number]]')->as_ui_validation()->placeholder(trans('admin::user.label_contact_home'))->ng_model('user.profile.contact_home') }}</div>
                                <div class="col-lg-5">{{ Former::text('profile.contact_mobile')->class('validate[required,custom[number]]')->as_ui_validation()->placeholder(trans('admin::user.label_contact_mobile'))->ng_model('user.profile.contact_mobile') }}</div>
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
                                <div class="col-lg-10">{{ Former::text('profile.address_street')
                                                            ->class('validate[required]')
                                                            ->ng_model('user.profile.address_street')
                                                            ->as_ui_validation() }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('address_area')->class('control-label col-lg-2') }}
                                <div class="col-lg-8">{{ Former::text('profile.address_area')
                                                            ->class('validate[required]')
                                                            ->ng_model('user.profile.address_area')
                                                            ->as_ui_validation()
                                                            ->placeholder(trans('admin::user.label_address_area')) }}</div>
                                <div class="col-lg-2">{{ Former::text('profile.address_postcode')
                                                            ->class('validate[required,custom[number]]')
                                                            ->ng_model('user.profile.address_postcode')
                                                            ->as_ui_validation()
                                                            ->placeholder(trans('admin::user.label_address_postcode')) }}</div>
                            </div>
                            <div class="form-group">
                                {{ Former::label('address_citystate')->class('control-label col-lg-2') }}
                                <div class="col-lg-5">{{ Former::select('address_city')
                                                        ->fromQuery( Code::category('city')->get(), 'value', 'name' )
                                                        ->ng_model('user.profile.address_city')
                                                        ->ui_select2() }}</div>
                                <div class="col-lg-5">{{ Former::select('address_state')
                                                        ->fromQuery( Code::category('state')->get(), 'value', 'name' )
                                                        ->ng_model('user.profile.address_state')
                                                        ->ui_select2() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="well clearfix">
                    <div class="pull-right">
                        <button id="btnUpdateProfile" type="submit" class="btn btn-green" ng-click="user.$save()" ng-disabled="!validation.$error.controls.$valid">{{ trans('admin::user.btn_update_profile') }}</button>
                    </div>
                </div>
            {{ Former::close() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    @parent
    @javascripts('user')
    <script language="JavaScript" type="text/javascript">

        function controllerProfile($scope,Model,Validation){
            $scope.validation = Validation;
            $scope.user = Model.create('users').$find('{{ $user_profile->id }}');

            //[i] Changing email
            $scope.validateEmail = function(data){
                $scope.user.$rpc('change-email',{email:data});
            }
        }

    </script>
@stop