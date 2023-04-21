<!--
Polr, a minimalist URL shortening platform.
Copyright (C) 2013-2017 Chaoyi Zha

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
-->

<!DOCTYPE html>
<html ng-app="polr">
<head>
    <title>@section('title'){{env('APP_NAME')}}@show</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Leave this for stats --}}
    <meta name="generator" content="Polr {{env('POLR_VERSION')}}" />
    @yield('meta')

    {{-- Load Stylesheets --}}
    @if (env('APP_STYLESHEET'))
    <link rel="stylesheet" href="{{env('APP_STYLESHEET')}}">
    @else
    <link rel="stylesheet" href="/css/default-bootstrap.min.css">
    @endif

    <link href="/css/base.css" rel="stylesheet">
    <link href="/css/toastr.min.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">


    <link rel="preload" href="/css/cd373194f1cd2363.css" as="style" />
    <link rel="stylesheet" href="/css/cd373194f1cd2363.css" data-n-g="" />
    <noscript data-n-css=""></noscript>
    <script defer="" nomodule="" src="/js/polyfills-c67a75d1b6f99dc8.js"></script>
    <script src="/js/webpack-1372379e55543fa1.js" defer=""></script>
    <script src="/js/framework-2c79e2a64abdb08b.js" defer=""></script>
    <script src="/js/main-68ca0bf4e47d6812.js" defer=""></script>
    <script src="/js/_app-e47066870d3ed116.js" defer=""></script>
    <script src="/js/103-daecfbd9f1816bcd.js" defer=""></script>
    <script src="/js/index-70630b86a5895861.js" defer=""></script>
    <script src="/js/_buildManifest.js" defer=""></script>
    <script src="/js/_ssgManifest.js" defer=""></script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" href="/fonts/bf9389fa1cdead38-s.p.woff2" as="font" type="font/woff2" crossorigin="anonymous" data-next-font="size-adjust" />
    <link rel="preload" href="/fonts/74fe520c844fd9ce-s.p.woff2" as="font" type="font/woff2" crossorigin="anonymous" data-next-font="size-adjust" />

    <style data-href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap">
      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5vAA.woff) format('woff')
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Me5g.woff) format('woff')
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlvAA.woff) format('woff')
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fCRc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fABc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fCBc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+1F00-1FFF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fBxc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0370-03FF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fCxc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fChc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 300;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmSU5fBBc4AMP6lQ.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu72xKKTU1Kvnz.woff2) format('woff2');
        unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu5mxKKTU1Kvnz.woff2) format('woff2');
        unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu7mxKKTU1Kvnz.woff2) format('woff2');
        unicode-range: U+1F00-1FFF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4WxKKTU1Kvnz.woff2) format('woff2');
        unicode-range: U+0370-03FF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu7WxKKTU1Kvnz.woff2) format('woff2');
        unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu7GxKKTU1Kvnz.woff2) format('woff2');
        unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxKKTU1Kg.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfCRc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfABc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfCBc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+1F00-1FFF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfBxc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0370-03FF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfCxc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfChc4AMP6lbBP.woff2) format('woff2');
        unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
      }

      @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url(https://fonts.gstatic.com/s/roboto/v30/KFOlCnqEu92Fr1MmWUlfBBc4AMP6lQ.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
      }
    </style>

    <link rel="shortcut icon" href="/favicon.ico">
    @yield('css')
</head>
<body>
    @include('snippets.navbar')
    <div class="container">
        <div class="content-div @if (!isset($no_div_padding)) content-div-padding @endif @if (isset($large)) jumbotron large-content-div @endif">
            
            <div id="xx"></div>@yield('content')
        </div>
    </div>


    <script id="__NEXT_DATA__" type="application/json">
      {
        "props": {
          "pageProps": {}
        },
        "page": "/",
        "query": {},
        "buildId": "fgzO6BxOilqs-BsnKBZhJ",
        "nextExport": true,
        "autoExport": true,
        "isFallback": false,
        "scriptLoader": [{
          "type": "x-shader/x-vertex",
          "id": "shader-vertex-terrain-perlinsombrero",
          "strategy": "afterInteractive",
          "children": "\nvec3 mod289(vec3 x)\n{\n  return x - floor(x * (1.0 / 289.0)) * 289.0;\n}\n\nvec4 mod289(vec4 x)\n{\n  return x - floor(x * (1.0 / 289.0)) * 289.0;\n}\n\nvec4 permute(vec4 x)\n{\n  return mod289(((x*34.0)+1.0)*x);\n}\n\nvec4 taylorInvSqrt(vec4 r)\n{\n  return 1.79284291400159 - 0.85373472095314 * r;\n}\n\nvec3 fade(vec3 t) {\n  return t*t*t*(t*(t*6.0-15.0)+10.0);\n}\n\n\nfloat cnoise(vec3 P)\n{\n  vec3 Pi0 = floor(P);\n  vec3 Pi1 = Pi0 + vec3(1.0);\n  Pi0 = mod289(Pi0);\n  Pi1 = mod289(Pi1);\n  vec3 Pf0 = fract(P);\n  vec3 Pf1 = Pf0 - vec3(1.0);\n  vec4 ix = vec4(Pi0.x, Pi1.x, Pi0.x, Pi1.x);\n  vec4 iy = vec4(Pi0.yy, Pi1.yy);\n  vec4 iz0 = Pi0.zzzz;\n  vec4 iz1 = Pi1.zzzz;\n\n  vec4 ixy = permute(permute(ix) + iy);\n  vec4 ixy0 = permute(ixy + iz0);\n  vec4 ixy1 = permute(ixy + iz1);\n\n  vec4 gx0 = ixy0 * (1.0 / 7.0);\n  vec4 gy0 = fract(floor(gx0) * (1.0 / 7.0)) - 0.5;\n  gx0 = fract(gx0);\n  vec4 gz0 = vec4(0.5) - abs(gx0) - abs(gy0);\n  vec4 sz0 = step(gz0, vec4(0.0));\n  gx0 -= sz0 * (step(0.0, gx0) - 0.5);\n  gy0 -= sz0 * (step(0.0, gy0) - 0.5);\n\n  vec4 gx1 = ixy1 * (1.0 / 7.0);\n  vec4 gy1 = fract(floor(gx1) * (1.0 / 7.0)) - 0.5;\n  gx1 = fract(gx1);\n  vec4 gz1 = vec4(0.5) - abs(gx1) - abs(gy1);\n  vec4 sz1 = step(gz1, vec4(0.0));\n  gx1 -= sz1 * (step(0.0, gx1) - 0.5);\n  gy1 -= sz1 * (step(0.0, gy1) - 0.5);\n\n  vec3 g000 = vec3(gx0.x,gy0.x,gz0.x);\n  vec3 g100 = vec3(gx0.y,gy0.y,gz0.y);\n  vec3 g010 = vec3(gx0.z,gy0.z,gz0.z);\n  vec3 g110 = vec3(gx0.w,gy0.w,gz0.w);\n  vec3 g001 = vec3(gx1.x,gy1.x,gz1.x);\n  vec3 g101 = vec3(gx1.y,gy1.y,gz1.y);\n  vec3 g011 = vec3(gx1.z,gy1.z,gz1.z);\n  vec3 g111 = vec3(gx1.w,gy1.w,gz1.w);\n\n  vec4 norm0 = taylorInvSqrt(vec4(dot(g000, g000), dot(g010, g010), dot(g100, g100), dot(g110, g110)));\n  g000 *= norm0.x;\n  g010 *= norm0.y;\n  g100 *= norm0.z;\n  g110 *= norm0.w;\n  vec4 norm1 = taylorInvSqrt(vec4(dot(g001, g001), dot(g011, g011), dot(g101, g101), dot(g111, g111)));\n  g001 *= norm1.x;\n  g011 *= norm1.y;\n  g101 *= norm1.z;\n  g111 *= norm1.w;\n\n  float n000 = dot(g000, Pf0);\n  float n100 = dot(g100, vec3(Pf1.x, Pf0.yz));\n  float n010 = dot(g010, vec3(Pf0.x, Pf1.y, Pf0.z));\n  float n110 = dot(g110, vec3(Pf1.xy, Pf0.z));\n  float n001 = dot(g001, vec3(Pf0.xy, Pf1.z));\n  float n101 = dot(g101, vec3(Pf1.x, Pf0.y, Pf1.z));\n  float n011 = dot(g011, vec3(Pf0.x, Pf1.yz));\n  float n111 = dot(g111, Pf1);\n\n  vec3 fade_xyz = fade(Pf0);\n  vec4 n_z = mix(vec4(n000, n100, n010, n110), vec4(n001, n101, n011, n111), fade_xyz.z);\n  vec2 n_yz = mix(n_z.xy, n_z.zw, fade_xyz.y);\n  float n_xyz = mix(n_yz.x, n_yz.y, fade_xyz.x);\n  return 2.2 * n_xyz;\n}\n\nfloat pnoise(vec3 P, vec3 rep)\n{\n  vec3 Pi0 = mod(floor(P), rep);\n  vec3 Pi1 = mod(Pi0 + vec3(1.0), rep);\n  Pi0 = mod289(Pi0);\n  Pi1 = mod289(Pi1);\n  vec3 Pf0 = fract(P);\n  vec3 Pf1 = Pf0 - vec3(1.0);\n  vec4 ix = vec4(Pi0.x, Pi1.x, Pi0.x, Pi1.x);\n  vec4 iy = vec4(Pi0.yy, Pi1.yy);\n  vec4 iz0 = Pi0.zzzz;\n  vec4 iz1 = Pi1.zzzz;\n\n  vec4 ixy = permute(permute(ix) + iy);\n  vec4 ixy0 = permute(ixy + iz0);\n  vec4 ixy1 = permute(ixy + iz1);\n\n  vec4 gx0 = ixy0 * (1.0 / 7.0);\n  vec4 gy0 = fract(floor(gx0) * (1.0 / 7.0)) - 0.5;\n  gx0 = fract(gx0);\n  vec4 gz0 = vec4(0.5) - abs(gx0) - abs(gy0);\n  vec4 sz0 = step(gz0, vec4(0.0));\n  gx0 -= sz0 * (step(0.0, gx0) - 0.5);\n  gy0 -= sz0 * (step(0.0, gy0) - 0.5);\n\n  vec4 gx1 = ixy1 * (1.0 / 7.0);\n  vec4 gy1 = fract(floor(gx1) * (1.0 / 7.0)) - 0.5;\n  gx1 = fract(gx1);\n  vec4 gz1 = vec4(0.5) - abs(gx1) - abs(gy1);\n  vec4 sz1 = step(gz1, vec4(0.0));\n  gx1 -= sz1 * (step(0.0, gx1) - 0.5);\n  gy1 -= sz1 * (step(0.0, gy1) - 0.5);\n\n  vec3 g000 = vec3(gx0.x,gy0.x,gz0.x);\n  vec3 g100 = vec3(gx0.y,gy0.y,gz0.y);\n  vec3 g010 = vec3(gx0.z,gy0.z,gz0.z);\n  vec3 g110 = vec3(gx0.w,gy0.w,gz0.w);\n  vec3 g001 = vec3(gx1.x,gy1.x,gz1.x);\n  vec3 g101 = vec3(gx1.y,gy1.y,gz1.y);\n  vec3 g011 = vec3(gx1.z,gy1.z,gz1.z);\n  vec3 g111 = vec3(gx1.w,gy1.w,gz1.w);\n\n  vec4 norm0 = taylorInvSqrt(vec4(dot(g000, g000), dot(g010, g010), dot(g100, g100), dot(g110, g110)));\n  g000 *= norm0.x;\n  g010 *= norm0.y;\n  g100 *= norm0.z;\n  g110 *= norm0.w;\n  vec4 norm1 = taylorInvSqrt(vec4(dot(g001, g001), dot(g011, g011), dot(g101, g101), dot(g111, g111)));\n  g001 *= norm1.x;\n  g011 *= norm1.y;\n  g101 *= norm1.z;\n  g111 *= norm1.w;\n\n  float n000 = dot(g000, Pf0);\n  float n100 = dot(g100, vec3(Pf1.x, Pf0.yz));\n  float n010 = dot(g010, vec3(Pf0.x, Pf1.y, Pf0.z));\n  float n110 = dot(g110, vec3(Pf1.xy, Pf0.z));\n  float n001 = dot(g001, vec3(Pf0.xy, Pf1.z));\n  float n101 = dot(g101, vec3(Pf1.x, Pf0.y, Pf1.z));\n  float n011 = dot(g011, vec3(Pf0.x, Pf1.yz));\n  float n111 = dot(g111, Pf1);\n\n  vec3 fade_xyz = fade(Pf0);\n  vec4 n_z = mix(vec4(n000, n100, n010, n110), vec4(n001, n101, n011, n111), fade_xyz.z);\n  vec2 n_yz = mix(n_z.xy, n_z.zw, fade_xyz.y);\n  float n_xyz = mix(n_yz.x, n_yz.y, fade_xyz.x);\n  return 2.2 * n_xyz;\n}\n\nfloat rand(vec2 n)\n{\n  return 0.5 + 0.5 *\n     fract(sin(dot(n.xy, vec2(12.9898, 78.233)))* 43758.5453);\n}\n\nvarying vec2  v_uv;\nvarying vec3  v_line_color;\n\n\nuniform float time;\nuniform float speed;\nuniform float elevation;\nuniform float noise_range;\nuniform float perlin_passes;\nuniform float sombrero_amplitude;\nuniform float sombrero_frequency;\nuniform vec3  line_color;\nvarying float z;\n\n#define M_PI 3.1415926535897932384626433832795\n\nvoid main()\n{\n    gl_PointSize = 1.;\n    v_uv          = uv;\n    v_line_color   = line_color;\n\n    // First perlin passes\n\n    float displacement  = pnoise( .4 * position + vec3( 0, speed * time, 0 ), vec3( 100.0 ) ) * 1. * noise_range;\n\n    if( perlin_passes \u003e 2.0 ){\n\n      displacement       += pnoise( 2. * position + vec3( 0, speed * time * 5., 0 ), vec3( 100. ) ) * .3 * noise_range;\n      displacement       += pnoise( 8. * position + vec3( 0, speed * time * 20., 0 ), vec3( 100. ) ) * .1 * noise_range;\n\n    }\n    else if(perlin_passes \u003e 1.0){\n\n      displacement       += pnoise( 8. * position + vec3( 0, speed * time * 20., 0 ), vec3( 100. ) ) * .1 * noise_range;\n    }\n\n\n    float distance = sqrt(((uv.x-0.5) * (uv.x-0.5)) + ((uv.y-0.5) * (uv.y-0.5)));\n    float z = (sombrero_amplitude * sin(((time * 0.5 * speed) - (distance * sombrero_frequency)) * M_PI));\n\n\n\n\n\n    // Sinus\n    displacement = displacement + (sin(position.x / 2. - M_PI / 2.)) * elevation;\n\n    vec3 newPosition = vec3(position.x,position.y,displacement + z);\n    gl_Position      = projectionMatrix * modelViewMatrix * vec4( newPosition, 1. );\n\n    z = newPosition.z;\n}\n"
        }, {
          "type": "x-shader/x-fragment",
          "id": "shader-fragment-terrain",
          "strategy": "afterInteractive",
          "children": "\n                    varying vec2 v_uv;\n                    varying vec3 v_line_color;\n                    varying float z;\n                    #define M_PI 3.1415926535897932384626433832795\n                    void main()\n                    {\n                        vec4 temp;\n                        float alpha = sin(v_uv.y * M_PI) / 4.;\n                        temp = vec4(v_line_color, alpha);\n                        gl_FragColor = temp;\n                    }\n          "
        }, {
          "src": "/anim.js",
          "strategy": "afterInteractive"
        }]
      }
    </script>

    {{-- Load JavaScript dependencies --}}
    <script src="/js/constants.js"></script>
    <script src="/js/jquery-1.11.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/angular.min.js"></script>
    <script src="/js/toastr.min.js"></script>
    <script src="/js/base.js"></script>

    <script>
    @if (Session::has('info'))
        toastr["info"](`{{ str_replace('`', '\`', session('info')) }}`, "Info")
    @endif
    @if (Session::has('error'))
        toastr["error"](`{{str_replace('`', '\`', session('error')) }}`, "Error")
    @endif
    @if (Session::has('warning'))
        toastr["warning"](`{{ str_replace('`', '\`', session('warning')) }}`, "Warning")
    @endif
    @if (Session::has('success'))
        toastr["success"](`{{ str_replace('`', '\`', session('success')) }}`, "Success")
    @endif

    @if (count($errors) > 0)
        // Handle Lumen validation errors
        @foreach ($errors->all() as $error)
            toastr["error"](`{{ str_replace('`', '\`', $error) }}`, "Error")
        @endforeach
    @endif
    </script>

    @yield('js')
</body>
</html>
