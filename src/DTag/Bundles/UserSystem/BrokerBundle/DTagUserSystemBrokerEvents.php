<?php

namespace DTag\Bundles\UserSystem\BrokerBundle;

final class DTagUserSystemBrokerEvents
{
    const CHANGE_PASSWORD_INITIALIZE = 'd_tag_user_system_broker.change_password.edit.initialize';
    const CHANGE_PASSWORD_SUCCESS = 'd_tag_user_system_broker.change_password.edit.success';
    const CHANGE_PASSWORD_COMPLETED = 'd_tag_user_system_broker.change_password.edit.completed';
    const GROUP_CREATE_INITIALIZE = 'd_tag_user_system_broker.group.create.initialize';
    const GROUP_CREATE_SUCCESS = 'd_tag_user_system_broker.group.create.success';
    const GROUP_CREATE_COMPLETED = 'd_tag_user_system_broker.group.create.completed';
    const GROUP_DELETE_COMPLETED = 'd_tag_user_system_broker.group.delete.completed';
    const GROUP_EDIT_INITIALIZE = 'd_tag_user_system_broker.group.edit.initialize';
    const GROUP_EDIT_SUCCESS = 'd_tag_user_system_broker.group.edit.success';
    const GROUP_EDIT_COMPLETED = 'd_tag_user_system_broker.group.edit.completed';
    const PROFILE_EDIT_INITIALIZE = 'd_tag_user_system_broker.profile.edit.initialize';
    const PROFILE_EDIT_SUCCESS = 'd_tag_user_system_broker.profile.edit.success';
    const PROFILE_EDIT_COMPLETED = 'd_tag_user_system_broker.profile.edit.completed';
    const REGISTRATION_INITIALIZE = 'd_tag_user_system_broker.registration.initialize';
    const REGISTRATION_SUCCESS = 'd_tag_user_system_broker.registration.success';
    const REGISTRATION_COMPLETED = 'd_tag_user_system_broker.registration.completed';
    const REGISTRATION_CONFIRM = 'd_tag_user_system_broker.registration.confirm';
    const REGISTRATION_CONFIRMED = 'd_tag_user_system_broker.registration.confirmed';
    const RESETTING_RESET_INITIALIZE = 'd_tag_user_system_broker.resetting.reset.initialize';
    const RESETTING_RESET_SUCCESS = 'd_tag_user_system_broker.resetting.reset.success';
    const RESETTING_RESET_COMPLETED = 'd_tag_user_system_broker.resetting.reset.completed';
    const SECURITY_IMPLICIT_LOGIN = 'd_tag_user_system_broker.security.implicit_login';
}
