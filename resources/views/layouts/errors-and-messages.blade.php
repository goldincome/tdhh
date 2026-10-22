
@if($errors->any())
    <div>
        @foreach($errors->all() as $error)
            <div class="alert alert-danger text-center mb-4">
                {!! $error !!}
                {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}
            </div>
        @endforeach
    </div>
@endif


@foreach (['danger', 'warning', 'success', 'info', 'error'] as $msg)
    <div class="">
        @if(\Illuminate\Support\Facades\Session::has($msg))
            <div class="alert alert-{{ ($msg == 'error' || $msg == 'danger') ? 'danger' : $msg }}">{!! \Illuminate\Support\Facades\Session::get( $msg) !!}
                {{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}
            </div>
        @endif
    </div>
@endforeach
