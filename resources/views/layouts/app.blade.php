@include('blog::layouts.components.header')
<!-- Bootstrap row -->
<div class="row" id="body-row">
@include('blog::layouts.components.sidebar')
<!-- MAIN -->

    <div class="col px-md-4">
        @if(session()->has('message'))
            <div class="alert alert-success">{{session()->pull('message')}}</div>
        @elseif(session()->has('error'))
            <div class="alert alert-warning">{{session()->pull('error')}}</div>
        @endif
        <nav aria-label="breadcrumb">
            <div class="row bg-light mt-3 m-0 p-0">
                <div class="col-9">
                    <ol class="breadcrumb bg-transparent m-0 mb-2">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        @yield('breadcrumb')
                    </ol>
                </div>
                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text d-xs-none" id="voiceCommandMessage">Click mic to start</span>
                        </div>
                        <select id="voiceCommandLanguage" class="p-0 m-0 form-control">
                            <option value="bn-BD">Bangla</option>
                            <option value="en-IN">English (India)</option>
                            <option value="en-US">English (USA)</option>
                            <option value="en-UK">English (UK)</option>
                        </select>
                        <div class="input-group-append">
                            <button class="text-gray btn btn-secondary" id="initVoiceRecognitionCommand"><i
                                    class="fa fa-microphone"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </nav>
        <div class="row border-bottom border-light">
            <h3 class="col-6">@yield('header')</h3>
            <div class="col-6 text-right">
                @yield('tools')
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-md-3">
                @yield('content')
            </div>
        </div>

    </div>
</div><!-- Main Col END -->
</div><!-- body-row END -->

@include('blog::layouts.components.footer')
