@props(['schema' => null])

@php
    // A page may publish several schemas, for example the business entity
    // plus a breadcrumb trail plus the listing itself. A single schema is
    // still accepted so a view can publish one node on its own.
    $schemas = [];

    if (! empty($schema)) {
        $schemas = array_is_list($schema) ? $schema : [$schema];
    }
@endphp

@foreach($schemas as $item)
    @if(! empty($item))
<script type="application/ld+json">
{!! json_encode($item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
    @endif
@endforeach
