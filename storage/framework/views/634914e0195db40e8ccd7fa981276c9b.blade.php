<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['href','xOn:click.prevent'])
<x-dropdown-link :href="$href" :x-on:click.prevent="$xOnClickPrevent" >

{{ $slot ?? "" }}
</x-dropdown-link>