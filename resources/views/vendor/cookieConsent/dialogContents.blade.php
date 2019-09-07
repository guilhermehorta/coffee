<div class="js-cookie-consent cookie-consent">

    <span class="cookie-consent__message">
        {!! trans('cookieConsent::texts.message') !!}
    </span>

    <button class="js-cookie-consent-agree cookie-consent__agree">
        {{ trans('cookieConsent::texts.agree') }}
    </button>

    <span class="cookie-consent__moreInfo">
        {!! trans('cookieConsent::texts.moreInfo') !!}
        <a href="/cookies" class="btn btn-default" role="button">{!! trans('cookieConsent::texts.here') !!}</a>
    </span>

</div>
