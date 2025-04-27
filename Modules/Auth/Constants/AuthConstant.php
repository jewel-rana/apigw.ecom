<?php

namespace Modules\Auth\Constants;

class AuthConstant
{
    const DASHBOARD                 = '/dashboard';
    const SIGN_IN                   = 'SIGN_IN';
    const RESET_PASSWORD            = 'RESET_PASSWORD';
    const FORGOT_PASSWORD_OTP_TYPE  = 'FORGOT_PASSWORD_OTP';
    const DEFAULT_OTP               = 111111;
    const USER_EDITABLE             = 1;
    const USER_NOT_EDITABLE         = 0;
    const USER_IS_SYSTEM            = 0;
    const USER_ACTIVE               = 1;
    const RESET_OTP_TYPE            = 'reset.otp';
    const LOGIN_OTP_TYPE            = 'user.login';
    const CUSTOMER_LOGIN_OTP_TYPE   = 'customer.login';
    const TOKEN_NAME                = 'Kartat Personal Access Client';
    const CUSTOMER_REGISTER_OTP_TYPE= 'customer.register';
    const CUSTOMER_PENDING          = 'pending';
    const CUSTOMER_ACTIVE           = 'active';
    const CUSTOMER_INACTIVE           = 'inactive';
    const CUSTOMER_FORGOT_OTP_TYPE  = 'customer.forgot';
}
