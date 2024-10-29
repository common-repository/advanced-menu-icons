<?php
function rami_svg_alowed_html(){
    $allowed_html = [
        'img' => [
            'src'       => [],
            'style'     => [],
            'width'     => [],
            'height'    => [],
        ],
        'svg' => [
            'style'       => [],
            'stroke'      => [],
            'xmlns'       => [],
            'fill'        => [],
            'viewbox'     => [],
            'role'        => [],
            'aria-hidden' => [],
            'focusable'   => [],
            'height'      => [],
            'width'       => [],
            'preserveAspectRatio'       => [],
        ],
        'g' => [ // Group tag
            'fill'             => [],
            'fill-rule'             => [],
            'stroke'           => [],
            'stroke-width'     => [],
            'stroke-linecap'   => [],
            'stroke-linejoin'  => [],
            'stroke-dasharray' => [],
            'stroke-dashoffset' => [],
            'transform'        => [],
        ],
        'rect' => [
            'width'            => [],
            'height'           => [],
            'x'                => [],
            'y'                => [],
            'rx'               => [], // For rounded rectangles
            'ry'               => [],
            'fill-opacity'     => [],
        ],
        'circle' => [
            'cx'               => [],
            'cy'               => [],
            'r'                => [],
        ],
        'ellipse' => [
            'cx'               => [],
            'cy'               => [],
            'rx'               => [],
            'ry'               => [],
        ],
        'line' => [
            'x1'               => [],
            'x2'               => [],
            'y1'               => [],
            'y2'               => [],
            'stroke'           => [],
            'stroke-width'     => [],
            'stroke-linecap'   => [],
        ],
        'polygon' => [
            'points'           => [],
        ],
        'polyline' => [
            'points'           => [],
        ],
        'text' => [
            'x'                => [],
            'y'                => [],
            'font-family'      => [],
            'font-size'        => [],
            'text-anchor'      => [],
        ],
        'path' => [
            'd'                => [],
            'fill'             => [],
            'stroke'           => [],
            'stroke-width'     => [],
            'stroke-opacity'   => [],
            'stroke-linecap'   => [],
            'stroke-linejoin'  => [],
            'stroke-dasharray' => [],
            'stroke-dashoffset'=> [],
            'clip-rule'        => [],
            'fill-rule'        => [],
        ],
        'filter' => [
            'id'               => [],
        ],
        'feGaussianBlur' => [
            'stdDeviation'     => [],
            'in'               => [],
        ],
    ];

    return $allowed_html;
}