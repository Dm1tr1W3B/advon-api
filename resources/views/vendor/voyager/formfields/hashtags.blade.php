
@php

    if(isset($row->details->type) && $row->details->type=='array' && is_array($dataTypeContent->{$row->field}))
        $value=  join(',', $dataTypeContent->{$row->field});
    else
        $value=$dataTypeContent->{$row->field};


@endphp
@if(isset($view) && ($view == 'browse' || $view == 'read'))
    <input readonly name='{{$row->field}}' value='{{ $value }}'>

@else
<input  name='{{$row->field}}' value='{{ $value }}'>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.1.1/tagify.min.js"
        integrity="sha512-V/mrpehNcIkQ5rszd9LwfBujHn0FJkHO0hBQc059gH5LWg1Tsnt7xphn7Z5jaDiq/xyLU4/BXi5jJauqM4PhlQ=="
        crossorigin="anonymous"></script>
<script>
    // The DOM element you wish to replace with Tagify

    var input = document.querySelector('input[name={{$row->field}}]');

    // initialize Tagify on the above input node reference
    new Tagify(input, {
            <?php if(isset($row->details->type) && $row->details->type == 'array'): ?>
            originalInputValueFormat: valuesArr => valuesArr.map(item => item.value)


            <?php else: ?>
            originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(', ')

            <?php endif; ?>
        }
    )
</script>
<style>
    :root {
        --tagify-dd-color-primary: rgb(53, 149, 246);
        --tagify-dd-bg-color: white
    }

    .tagify {
        --tags-border-color: #DDD;
        --tags-hover-border-color: #CCC;
        --tags-focus-border-color: #3595f6;
        --tag-bg: #E5E5E5;
        --tag-hover: #D3E2E2;
        --tag-text-color: black;
        --tag-text-color--edit: black;
        --tag-pad: 0.3em 0.5em;
        --tag-inset-shadow-size: 1.1em;
        --tag-invalid-color: #D39494;
        --tag-invalid-bg: rgba(211, 148, 148, 0.5);
        --tag-remove-bg: rgba(211, 148, 148, 0.3);
        --tag-remove-btn-color: black;
        --tag-remove-btn-bg: none;
        --tag-remove-btn-bg--hover: #c77777;
        --input-color: inherit;
        --tag--min-width: 1ch;
        --tag--max-width: auto;
        --tag-hide-transition: 0.3s;
        --placeholder-color: rgba(0, 0, 0, 0.4);
        --placeholder-color-focus: rgba(0, 0, 0, 0.25);
        --loader-size: .8em;
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
        border: 1px solid #ddd;
        border: 1px solid var(--tags-border-color);
        padding: 0;
        line-height: normal;
        cursor: text;
        outline: 0;
        position: relative;
        box-sizing: border-box;
        transition: .1s
    }

    @keyframes tags--bump {
        30% {
            transform: scale(1.2)
        }
    }

    @keyframes rotateLoader {
        to {
            transform: rotate(1turn)
        }
    }

    .tagify:hover {
        border-color: #ccc;
        border-color: var(--tags-hover-border-color)
    }

    .tagify.tagify--focus {
        transition: 0s;
        border-color: #3595f6;
        border-color: var(--tags-focus-border-color)
    }

    .tagify[readonly]:not(.tagify--mix) {
        cursor: default
    }

    .tagify[readonly]:not(.tagify--mix) > .tagify__input {
        visibility: hidden;
        width: 0;
        margin: 5px 0
    }

    .tagify[readonly]:not(.tagify--mix) .tagify__tag > div {
        padding: .3em .5em;
        padding: var(--tag-pad)
    }

    .tagify[readonly]:not(.tagify--mix) .tagify__tag > div::before {
        background: linear-gradient(45deg, var(--tag-bg) 25%, transparent 25%, transparent 50%, var(--tag-bg) 50%, var(--tag-bg) 75%, transparent 75%, transparent) 0/5px 5px;
        box-shadow: none;
        filter: brightness(.95)
    }

    .tagify[readonly] .tagify__tag__removeBtn {
        display: none
    }

    .tagify--loading .tagify__input > br:last-child {
        display: none
    }

    .tagify--loading .tagify__input::before {
        content: none
    }

    .tagify--loading .tagify__input::after {
        content: '';
        vertical-align: middle;
        opacity: 1;
        width: .7em;
        height: .7em;
        width: var(--loader-size);
        height: var(--loader-size);
        border: 3px solid;
        border-color: #eee #bbb #888 transparent;
        border-radius: 50%;
        animation: rotateLoader .4s infinite linear;
        content: '' !important;
        margin: -2px 0 -2px .5em
    }

    .tagify--loading .tagify__input:empty::after {
        margin-left: 0
    }

    .tagify + input, .tagify + textarea {
        position: absolute !important;
        left: -9999em !important;
        transform: scale(0) !important
    }

    .tagify__tag {
        display: inline-flex;
        align-items: center;
        margin: 5px 0 5px 5px;
        position: relative;
        z-index: 1;
        outline: 0;
        cursor: default;
        transition: .13s ease-out
    }

    .tagify__tag > div {
        vertical-align: top;
        box-sizing: border-box;
        max-width: 100%;
        padding: .3em .5em;
        padding: var(--tag-pad, .3em .5em);
        color: #000;
        color: var(--tag-text-color, #000);
        line-height: inherit;
        border-radius: 3px;
        white-space: nowrap;
        transition: .13s ease-out
    }

    .tagify__tag > div > * {
        white-space: pre-wrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: top;
        min-width: 1ch;
        max-width: auto;
        min-width: var(--tag--min-width, 1ch);
        max-width: var(--tag--max-width, auto);
        transition: .8s ease, .1s color
    }

    .tagify__tag > div > [contenteditable] {
        outline: 0;
        -webkit-user-select: text;
        user-select: text;
        cursor: text;
        margin: -2px;
        padding: 2px;
        max-width: 350px
    }

    .tagify__tag > div::before {
        content: '';
        position: absolute;
        border-radius: inherit;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
        pointer-events: none;
        transition: 120ms ease;
        animation: tags--bump .3s ease-out 1;
        box-shadow: 0 0 0 1.1em #e5e5e5 inset;
        box-shadow: 0 0 0 var(--tag-inset-shadow-size, 1.1em) var(--tag-bg, #e5e5e5) inset
    }

    .tagify__tag:focus div::before, .tagify__tag:hover:not([readonly]) div::before {
        top: -2px;
        right: -2px;
        bottom: -2px;
        left: -2px;
        box-shadow: 0 0 0 1.1em #d3e2e2 inset;
        box-shadow: 0 0 0 var(--tag-inset-shadow-size, 1.1em) var(--tag-hover, #d3e2e2) inset
    }

    .tagify__tag--loading {
        pointer-events: none
    }

    .tagify__tag--loading .tagify__tag__removeBtn {
        display: none
    }

    .tagify__tag--loading::after {
        --loader-size: .4em;
        content: '';
        vertical-align: middle;
        opacity: 1;
        width: .7em;
        height: .7em;
        width: var(--loader-size);
        height: var(--loader-size);
        border: 3px solid;
        border-color: #eee #bbb #888 transparent;
        border-radius: 50%;
        animation: rotateLoader .4s infinite linear;
        margin: 0 .5em 0 -.1em
    }

    .tagify__tag--flash div::before {
        animation: none
    }

    .tagify__tag--hide {
        width: 0 !important;
        padding-left: 0;
        padding-right: 0;
        margin-left: 0;
        margin-right: 0;
        opacity: 0;
        transform: scale(0);
        transition: .3s;
        transition: var(--tag-hide-transition, .3s);
        pointer-events: none
    }

    .tagify__tag--hide > div > * {
        white-space: nowrap
    }

    .tagify__tag.tagify--noAnim > div::before {
        animation: none
    }

    .tagify__tag.tagify--notAllowed:not(.tagify__tag--editable) div > span {
        opacity: .5
    }

    .tagify__tag.tagify--notAllowed:not(.tagify__tag--editable) div::before {
        box-shadow: 0 0 0 1.1em rgba(211, 148, 148, .5) inset !important;
        box-shadow: 0 0 0 var(--tag-inset-shadow-size, 1.1em) var(--tag-invalid-bg, rgba(211, 148, 148, .5)) inset !important;
        transition: .2s
    }

    .tagify__tag[readonly] .tagify__tag__removeBtn {
        display: none
    }

    .tagify__tag[readonly] > div::before {
        background: linear-gradient(45deg, var(--tag-bg) 25%, transparent 25%, transparent 50%, var(--tag-bg) 50%, var(--tag-bg) 75%, transparent 75%, transparent) 0/5px 5px;
        box-shadow: none;
        filter: brightness(.95)
    }

    .tagify__tag--editable > div {
        color: #000;
        color: var(--tag-text-color--edit, #000)
    }

    .tagify__tag--editable > div::before {
        box-shadow: 0 0 0 2px #d3e2e2 inset !important;
        box-shadow: 0 0 0 2px var(--tag-hover, #d3e2e2) inset !important
    }

    .tagify__tag--editable > .tagify__tag__removeBtn {
        pointer-events: none
    }

    .tagify__tag--editable > .tagify__tag__removeBtn::after {
        opacity: 0;
        transform: translateX(100%) translateX(5px)
    }

    .tagify__tag--editable.tagify--invalid > div::before {
        box-shadow: 0 0 0 2px #d39494 inset !important;
        box-shadow: 0 0 0 2px var(--tag-invalid-color, #d39494) inset !important
    }

    .tagify__tag__removeBtn {
        order: 5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        cursor: pointer;
        font: 14px/1 Arial;
        background: 0 0;
        background: var(--tag-remove-btn-bg, none);
        color: #000;
        color: var(--tag-remove-btn-color, #000);
        width: 14px;
        height: 14px;
        margin-right: 4.66667px;
        margin-left: auto;
        overflow: hidden;
        transition: .2s ease-out
    }

    .tagify__tag__removeBtn::after {
        content: "\00D7";
        transition: .3s, color 0s
    }

    .tagify__tag__removeBtn:hover {
        color: #fff;
        background: #c77777;
        background: var(--tag-remove-btn-bg--hover, #c77777)
    }

    .tagify__tag__removeBtn:hover + div > span {
        opacity: .5
    }

    .tagify__tag__removeBtn:hover + div::before {
        box-shadow: 0 0 0 1.1em rgba(211, 148, 148, .3) inset !important;
        box-shadow: 0 0 0 var(--tag-inset-shadow-size, 1.1em) var(--tag-remove-bg, rgba(211, 148, 148, .3)) inset !important;
        transition: box-shadow .2s
    }

    .tagify:not(.tagify--mix) .tagify__input br {
        display: none
    }

    .tagify:not(.tagify--mix) .tagify__input * {
        display: inline;
        white-space: nowrap
    }

    .tagify__input {
        flex-grow: 1;
        display: inline-block;
        min-width: 110px;
        margin: 5px;
        padding: .3em .5em;
        padding: var(--tag-pad, .3em .5em);
        line-height: inherit;
        position: relative;
        white-space: pre-wrap;
        color: inherit;
        color: var(--input-color, inherit);
        box-sizing: inherit
    }

    .tagify__input:empty::before {
        transition: .2s ease-out;
        opacity: 1;
        transform: none;
        display: inline-block;
        width: auto
    }

    .tagify--mix .tagify__input:empty::before {
        display: inline-block
    }

    .tagify__input:focus {
        outline: 0
    }

    .tagify__input:focus::before {
        transition: .2s ease-out;
        opacity: 0;
        transform: translatex(6px)
    }

    @media all and (-ms-high-contrast: none),(-ms-high-contrast: active) {
        .tagify__input:focus::before {
            display: none
        }
    }

    @supports (-ms-ime-align:auto) {
        .tagify__input:focus::before {
            display: none
        }
    }

    .tagify__input:focus:empty::before {
        transition: .2s ease-out;
        opacity: 1;
        transform: none;
        color: rgba(0, 0, 0, .25);
        color: var(--placeholder-color-focus)
    }

    @-moz-document url-prefix() {
        .tagify__input:focus:empty::after {
            display: none
        }
    }

    .tagify__input::before {
        content: attr(data-placeholder);
        height: 1em;
        line-height: 1em;
        margin: auto 0;
        z-index: 1;
        color: rgba(0, 0, 0, .4);
        color: var(--placeholder-color);
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        position: absolute
    }

    .tagify--mix .tagify__input::before {
        display: none;
        position: static;
        line-height: inherit
    }

    .tagify__input::after {
        content: attr(data-suggest);
        display: inline-block;
        white-space: pre;
        color: #000;
        opacity: .3;
        pointer-events: none;
        max-width: 100px
    }

    .tagify__input .tagify__tag {
        margin: 0 1px
    }

    .tagify__input .tagify__tag > div {
        padding-top: 0;
        padding-bottom: 0
    }

    .tagify--mix {
        display: block
    }

    .tagify--mix .tagify__input {
        padding: 5px;
        margin: 0;
        width: 100%;
        height: 100%;
        line-height: 1.5;
        display: block
    }

    .tagify--mix .tagify__input::before {
        height: auto
    }

    .tagify--mix .tagify__input::after {
        content: none
    }

    .tagify--select::after {
        content: '>';
        opacity: .5;
        position: absolute;
        top: 50%;
        right: 0;
        bottom: 0;
        font: 16px monospace;
        line-height: 8px;
        height: 8px;
        pointer-events: none;
        transform: translate(-150%, -50%) scaleX(1.2) rotate(90deg);
        transition: .2s ease-in-out
    }

    .tagify--select[aria-expanded=true]::after {
        transform: translate(-150%, -50%) rotate(270deg) scaleY(1.2)
    }

    .tagify--select .tagify__tag {
        position: absolute;
        top: 0;
        right: 1.8em;
        bottom: 0
    }

    .tagify--select .tagify__tag div {
        display: none
    }

    .tagify--select .tagify__input {
        width: 100%
    }

    .tagify--invalid {
        --tags-border-color: #D39494
    }

    .tagify__dropdown {
        position: absolute;
        z-index: 9999;
        transform: translateY(1px);
        overflow: hidden
    }

    .tagify__dropdown[placement=top] {
        margin-top: 0;
        transform: translateY(-100%)
    }

    .tagify__dropdown[placement=top] .tagify__dropdown__wrapper {
        border-top-width: 1px;
        border-bottom-width: 0
    }

    .tagify__dropdown[position=text] {
        box-shadow: 0 0 0 3px rgba(var(--tagify-dd-color-primary), .1);
        font-size: .9em
    }

    .tagify__dropdown[position=text] .tagify__dropdown__wrapper {
        border-width: 1px
    }

    .tagify__dropdown__wrapper {
        max-height: 300px;
        overflow: hidden;
        background: #fff;
        background: var(--tagify-dd-bg-color);
        border: 1px solid #3595f6;
        border-color: var(--tagify-dd-color-primary);
        border-width: 1.1px;
        border-top-width: 0;
        box-shadow: 0 2px 4px -2px rgba(0, 0, 0, .2);
        transition: .25s cubic-bezier(0, 1, .5, 1)
    }

    .tagify__dropdown__wrapper:hover {
        overflow: auto
    }

    .tagify__dropdown--initial .tagify__dropdown__wrapper {
        max-height: 20px;
        transform: translateY(-1em)
    }

    .tagify__dropdown--initial[placement=top] .tagify__dropdown__wrapper {
        transform: translateY(2em)
    }

    .tagify__dropdown__item {
        box-sizing: inherit;
        padding: .3em .5em;
        margin: 1px;
        cursor: pointer;
        border-radius: 2px;
        position: relative;
        outline: 0
    }

    .tagify__dropdown__item--active {
        background: #3595f6;
        background: var(--tagify-dd-color-primary);
        color: #fff
    }

    .tagify__dropdown__item:active {
        filter: brightness(105%)
    }
</style>
