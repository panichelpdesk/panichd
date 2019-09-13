@section('panichd_assets')
<style>
    /* Message at the bottom of the screen */
    /* Source code from https://www.w3schools.com/howto/howto_js_snackbar.asp */

    #bottom_toast {
      visibility: hidden;
      min-width: 250px;
      margin-left: -125px;
      text-align: center;
      border-radius: 2px;
      padding: 16px;
      position: fixed;
      z-index: 1;
      left: 50%;
      bottom: 30px;
      font-size: 17px;
    }

    #bottom_toast.show {
      visibility: visible;
      -webkit-animation: bottom_toast_fadein 0.5s @if(isset($toast_fadeout)) , bottom_toast_fadeout 0.5s {{ $toast_fadeout }}s @endif ;
      animation: bottom_toast_fadein 0.5s @if(isset($toast_fadeout)) , bottom_toast_fadeout 0.5s {{ $toast_fadeout }}s @endif ;
    }

    @-webkit-keyframes bottom_toast_fadein {
      from {bottom: 0; opacity: 0;}
      to {bottom: 30px; opacity: 1;}
    }

    @keyframes bottom_toast_fadein {
      from {bottom: 0; opacity: 0;}
      to {bottom: 30px; opacity: 1;}
    }

    @if(isset($toast_fadeout))
        @-webkit-keyframes bottom_toast_fadeout {
          from {bottom: 30px; opacity: 1;}
          to {bottom: 0; opacity: 0;}
        }

        @keyframes bottom_toast_fadeout {
          from {bottom: 30px; opacity: 1;}
          to {bottom: 0; opacity: 0;}
        }
    @endif
</style>
@append

@section('footer')
    <div id="bottom_toast">@if(isset($toast_html)){!! $toast_html !!}@endif</div>
@append
