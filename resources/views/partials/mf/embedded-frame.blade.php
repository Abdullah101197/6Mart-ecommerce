@php($frameId = $frameId ?? 'mf-frame')
@php($frameName = $frameName ?? null)
@php($frameSrc = $frameSrc ?? null)
@php($frameOffset = $frameOffset ?? 220)

<div class="mf-card" style="margin-top:14px;padding:0;overflow:hidden">
    <iframe
        id="{{ $frameId }}"
        @if($frameName) name="{{ $frameName }}" @endif
        src="{{ $frameSrc }}"
        style="width:100%;border:0;display:block;height:70vh;background:#fff"
        loading="lazy"
        data-mf-frame="1"
        data-mf-offset="{{ $frameOffset }}"
    ></iframe>
</div>

