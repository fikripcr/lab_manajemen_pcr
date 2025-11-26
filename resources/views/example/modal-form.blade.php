@props([
    'action' => '',
    'header' => 'on',
    'footer' => 'on',
    'name' => '',
    'size' => 'md',
    'title'=>''
])

<div class="modal fade" id="{{$name}}-modal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-{{$size}}">
        <form action="{{$action}}" method="post" id="{{$name}}-form">
            @csrf
            <div class="modal-content">
                @if($header == 'on')
                    <div class="modal-header" style="padding-bottom:0.5rem">
                        <h3 class="fw-bold"><i id="icon-form" class="fa fa-plus fa-fw fs-3 text-success"></i> {{$title}}</h3>
                        <div class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>
                @endif

                <div class="modal-body scroll-y mx-lg-2 my-2">
                    {{$slot}}
                </div>

                @if($footer=='on')
                    <div class="modal-footer ">
                        <x-theme.btn type="discard" />
                        @if($action)
                            <x-theme.btn type="submit-no-js" />
                        @else
                            <x-theme.btn type="submit" />
                        @endif
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
