<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class SiteHtml
{
    public static function icon(string $name, string $class = '', string $attrs = ''): HtmlString
    {
        $url = e(asset('images/icons/' . $name . '.png'));
        $class = e(trim('icon ' . $class));
        $attrs = $attrs !== '' ? ' ' . $attrs : '';

        return new HtmlString("<span class=\"{$class}\" style=\"--icon: url('{$url}')\" aria-hidden=\"true\"{$attrs}></span>");
    }

    public static function img(string $name, string $class = '', bool $eager = false, ?string $alt = null): HtmlString
    {
        $img = config('site-images.' . $name);

        if (! $img) {
            return new HtmlString('');
        }

        $class = e(trim('object-cover ' . $class));
        $alt = e($alt ?? $img['alt']);
        $loading = $eager ? 'fetchpriority="high"' : 'loading="lazy"';

        return new HtmlString(
            '<picture>'
            . '<source media="(max-width: 640px)" srcset="' . e(asset($img['sm'])) . '" width="' . $img['sw'] . '" height="' . $img['sh'] . '">'
            . '<img src="' . e(asset($img['src'])) . '" width="' . $img['w'] . '" height="' . $img['h'] . '" alt="' . $alt . '" decoding="async" ' . $loading . ' class="' . $class . '">'
            . '</picture>'
        );
    }
}
